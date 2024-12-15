<?php

namespace MoeMizrak\LaravelOpenrouter;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use JsonException;
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

    // Buffer variable for incomplete streaming data.
    private static string $buffer = '';

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
        $response = app(ClientInterface::class)->request(
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
     *
     * @return PromiseInterface
     */
    public function chatStreamRequest(ChatData $chatData): PromiseInterface
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
        $promise = app(ClientInterface::class)->requestAsync(
            'POST',
            $chatCompletionPath,
            $options
        );

        /*
         * Return streaming response promise which can be resolved with promise->wait().
         */
        return $promise->then(
            function (ResponseInterface $response) {
                return $response->getBody();
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
    public function filterStreamingResponse(string $streamingResponse): array
    {
        // Prepend any leftover data from the previous iteration
        $streamingResponse = self::$buffer . $streamingResponse;
        // Clear buffer
        self::$buffer = '';

        // Split the string by lines
        $lines = explode("\n", $streamingResponse);

        // Filter out unnecessary lines and decode the JSON data
        $responseDataArray = [];

        // Flag to indicate if the first line is a complete JSON
        $firstLineComplete = false;

        foreach ($lines as $line) {
            if (str_starts_with($line, 'data: ')) {
                // Remove "data: " prefix
                $jsonData = substr($line, strlen('data: '));

                try {
                    // Attempt to decode the JSON data
                    $data = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
                    $responseDataArray[] = $this->formChatResponse($data);
                    $firstLineComplete = true;
                } catch (JsonException $e) {
                    // If JSON decoding fails, buffer the line and continue
                    self::$buffer = $line;
                    continue;
                }
            } else if (trim($line) === '' && ! empty(self::$buffer)) {
                // If the line is empty and there's something in the buffer, try to process the buffer
                try {
                    // Attempt to decode the JSON data
                    $data = json_decode(self::$buffer, true, 512, JSON_THROW_ON_ERROR);
                    $responseDataArray[] = $this->formChatResponse($data);
                    self::$buffer = ''; // Clear buffer after successful processing
                } catch (JsonException $e) {
                    // If JSON decoding fails, retain the buffer for next iteration
                    continue;
                }
            } else if (! str_starts_with($line, 'data: ') && ! empty(trim($line))) {
                // If the line doesn't start with 'data: ', it might be part of a multiline JSON or a partial line
                if (! $firstLineComplete) {
                    // If it's the first line and not complete, assume it's part of the first JSON object
                    self::$buffer = $line;
                    $firstLineComplete = true; // Set flag to true after buffering incomplete first line
                } else {
                    // If it's not the first line or the first line is complete, buffer it for next iteration
                    self::$buffer .= $line;
                }
            } else {
                // Line does not contain 'data: ' and is not part of a multiline JSON, likely incomplete
                self::$buffer .= $line;
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
        $response = app(ClientInterface::class)->request(
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
        $response = app(ClientInterface::class)->request(
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
            'id'       => Arr::get($response, 'id'),
            'provider' => Arr::get($response, 'provider'),
            'model'    => Arr::get($response, 'model'),
            'object'   => Arr::get($response, 'object'),
            'created'  => Arr::get($response, 'created'),
            'choices'  => Arr::get($response, 'choices'),
            'usage'    => Arr::get($response, 'usage'),
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