<?php

namespace MoeMizrak\LaravelOpenrouter;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use MoeMizrak\LaravelOpenrouter\DTO\ChatData;
use MoeMizrak\LaravelOpenrouter\DTO\CostResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ErrorData;
use MoeMizrak\LaravelOpenrouter\DTO\LimitResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseData;
use Psr\Http\Message\ResponseInterface;
use Spatie\DataTransferObject\Arr;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

/**
 * OpenRouter request and formed response class.
 *
 * OpenRouter doc: https://openrouter.ai/docs
 *
 * Class OpenRouterRequest
 * @package MoeMizrak\LaravelOpenrouter
 */
class OpenRouterRequest extends OpenRouterAPI
{
    use DataHandlingTrait;

    /**
     * Sends a model request for the given chat conversation.
     *
     * @param ChatData $chatData
     *
     * @return ErrorData|ResponseData
     * @throws GuzzleException
     * @throws UnknownProperties
     */
    public function chatRequest(ChatData $chatData): ErrorData|ResponseData
    {
        // The path for the chat completion request.
        $chatCompletionPath = 'chat/completions';

        // Detect if stream chat completion is requested, and return ErrorData stating that chatStreamRequest needs to be used instead.
        if ($chatData->stream) {
            return new ErrorData([
                'code'    => 400,
                'message' => 'For stream chat completion please use "chatStreamRequest" method instead!',
            ]);
        }

        // Filter null values from the chatData object and return array.
        $chatData = $this->filterNullValuesRecursive($chatData);

        // Options for the Guzzle request
        $options = [
            'json' => $chatData,
        ];

        // Send POST request to the OpenRouter API chat completion endpoint and get the response.
        $response = $this->client->request(
            'POST',
            $chatCompletionPath,
            $options
        );

        // Decode the json response
        $response = $this->jsonDecode($response);

        return $this->formChatResponse($response);
    }

    /**
     * Sends a streaming request for the given chat conversation.
     *
     * @param ChatData $chatData
     * @param int $readByte - Default 4096 byte (4kB) for performance.
     *
     * @return PromiseInterface
     */
    public function chatStreamRequest(ChatData $chatData, int $readByte = 4096): PromiseInterface
    {
        // The path for the chat completion request.
        $chatCompletionPath = 'chat/completions';

        $chatData->stream = true;

        // Filter null values from the chatData object and return array.
        $chatData = $this->filterNullValuesRecursive($chatData);

        // Add headers for streaming.
        $headers = [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache'
        ];

        // Options for the Guzzle request
        $options = [
            'json'    => $chatData,
            'headers' => $headers
        ];

        // Send POST request to the OpenRouter API chat completion endpoint and get the streaming response.
        $promise = $this->client->requestAsync(
            'POST',
            $chatCompletionPath,
            $options
        );

        /*
         * Return streaming response promise which can be resolved with promise->wait().
         */
        return $promise->then(
            function (ResponseInterface $response) use($readByte) {
                $streamingResponse = $response->getBody()->read($readByte);

                return $this->filterStreamingResponse($streamingResponse);
            }
        );
    }

    /**
     * It filters streaming response string so that response string is mapped into ResponseData.
     *
     * @param string $streamingResponse
     *
     * @return array
     * @throws UnknownProperties
     */
    private function filterStreamingResponse(string $streamingResponse): array
    {
        // Split the string by lines
        $lines = explode("\n", $streamingResponse);

        // Filter out unnecessary lines
        $filteredLines = array_filter($lines, function($line) {
            // Check if line starts with "data: {"
            return strpos($line, 'data: {') === 0;
        });

        // Decode the JSON data in each line
        $responseDataArray = [];

        foreach ($filteredLines as $line) {
            // Remove "data: " and decode JSON
            $data = json_decode(substr($line, strlen('data: ')), true);
            // Check whether decoding was successful.
            if (json_last_error() === JSON_ERROR_NONE) {
                $responseDataArray[] = $this->formChatResponse($data);
            }
        }

        return $responseDataArray;
    }

    /**
     * Sends a cost request for the given generation id.
     *
     * @param string $generationId
     *
     * @return CostResponseData
     * @throws GuzzleException
     * @throws UnknownProperties
     */
    public function costRequest(string $generationId): CostResponseData
    {
        // The path for the cost and stats request. e.g. generation?id=$GENERATION_ID
        $costPath = 'generation?id=' . $generationId;

        // Send GET request to the OpenRouter API generation endpoint and get the response.
        $response = $this->client->request(
            'GET',
            $costPath
        );

        return $this->formCostsResponse($response);
    }

    /**
     * Sends limit request for the rate limit or credits left on an API key.
     *
     * @return LimitResponseData
     * @throws GuzzleException
     * @throws UnknownProperties
     */
    public function limitRequest(): LimitResponseData
    {
        // The path for the rate limit or credits left request.
        $limitPath = 'auth/key';

        // Send GET request to the OpenRouter API limit endpoint and get the response.
        $response = $this->client->request(
            'GET',
            $limitPath
        );

        return $this->formLimitResponse($response);
    }

    /**
     * Forms the response as ResponseData including id, model, object created, choices and usage if exits.
     *
     * @param mixed $response
     *
     * @return ResponseData
     * @throws UnknownProperties
     */
    private function formChatResponse(mixed $response = null) : ResponseData
    {
        // Map the response data to ResponseData and return it.
        return new ResponseData([
            'id'      => Arr::get($response, 'id'),
            'model'   => Arr::get($response, 'model'),
            'object'  => Arr::get($response, 'object'),
            'created' => Arr::get($response, 'created'),
            'choices' => Arr::get($response, 'choices'),
            'usage'   => Arr::get($response, 'usage'),
        ]);
    }

    /**
     * Forms the cost response as CostResponseData.
     * First decodes the json response, then map it in CostResponseData to return the response.
     *
     * @param ResponseInterface|null $response
     *
     * @return CostResponseData
     * @throws UnknownProperties
     */
    private function formCostsResponse(?ResponseInterface $response = null) : CostResponseData
    {
        // Decode the json response
        $response = $this->jsonDecode($response);

        // Map the response data to CostResponseData and return it.
        return new CostResponseData([
            'id'                       => Arr::get($response, 'data.id'),
            'model'                    => Arr::get($response, 'data.model'),
            'streamed'                 => Arr::get($response, 'data.streamed'),
            'total_cost'               => Arr::get($response, 'data.total_cost'),
            'origin'                   => Arr::get($response, 'data.origin'),
            'cancelled'                => Arr::get($response, 'data.cancelled'),
            'finish_reason'            => Arr::get($response, 'data.finish_reason'),
            'generation_time'          => Arr::get($response, 'data.generation_time'),
            'created_at'               => Arr::get($response, 'data.created_at'),
            'provider_name'            => Arr::get($response, 'data.provider_name'),
            'tokens_prompt'            => Arr::get($response, 'data.tokens_prompt'),
            'tokens_completion'        => Arr::get($response, 'data.tokens_completion'),
            'native_tokens_prompt'     => Arr::get($response, 'data.native_tokens_prompt'),
            'native_tokens_completion' => Arr::get($response, 'data.native_tokens_completion'),
            'num_media_prompt'         => Arr::get($response, 'data.num_media_prompt'),
            'num_media_completion'     => Arr::get($response, 'data.num_media_completion'),
            'app_id'                   => Arr::get($response, 'data.app_id'),
            'latency'                  => Arr::get($response, 'data.latency'),
            'moderation_latency'       => Arr::get($response, 'data.moderation_latency'),
            'upstream_id'              => Arr::get($response, 'data.upstream_id'),
            'usage'                    => Arr::get($response, 'data.usage'),
        ]);
    }

    /**
     * Forms the response as LimitResponseData
     * First decodes the json response and get the result, then map it in LimitResponseData to return the response.
     *
     * @param ResponseInterface|null $response
     *
     * @return LimitResponseData
     * @throws UnknownProperties
     */
    private function formLimitResponse(?ResponseInterface $response = null): LimitResponseData
    {
        // Decode the json response
        $response = $this->jsonDecode($response);

        // Map the response data to LimitResponseData and return it.
        return new LimitResponseData([
            'label'           => Arr::get($response, 'data.label'),
            'usage'           => Arr::get($response, 'data.usage'),
            'limit'           => Arr::get($response, 'data.limit'),
            'limit_remaining' => Arr::get($response, 'data.limit_remaining'),
            'is_free_tier'    => Arr::get($response, 'data.is_free_tier'),
            'rate_limit'      => Arr::get($response, 'data.rate_limit'),
        ]);
    }

    /**
     * Decodes response to json.
     *
     * @param ResponseInterface|null $response
     *
     * @return mixed|null
     */
    private function jsonDecode(?ResponseInterface $response = null): mixed
    {
        // Get the response body or return null.
        return ($response ? json_decode($response->getBody(), true) : null);
    }
}