<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Arr;
use MoeMizrak\LaravelOpenrouter\DTO\ChatData;
use MoeMizrak\LaravelOpenrouter\DTO\CostResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ErrorData;
use MoeMizrak\LaravelOpenrouter\DTO\LimitResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseData;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;

/**
 * OpenRouter request and formed response class.
 * OpenRouter doc: https://openrouter.ai/docs
 */
final class OpenRouterRequest extends OpenRouterAPI
{
    /**
     * Sends a model request for the given chat conversation.
     *
     * @throws ReflectionException|GuzzleException
     */
    public function chatRequest(ChatData $chatData): ErrorData|ResponseData
    {
        // The path for the chat completion request.
        $chatCompletionPath = 'chat/completions';

        // Detect if stream chat completion is requested, and return ErrorData stating that chatStreamRequest needs to be used instead.
        if ($chatData->stream) {
            return new ErrorData(
                code: 400,
                message: 'For stream chat completion please use "chatStreamRequest" method instead!',
            );
        }

        // Filter null values from the chatData object and return array.
        $chatData = $chatData->convertToArray();

        // Options for the Guzzle request
        $options = [
            'json' => $chatData,
        ];

        $response = app(ClientInterface::class)->request(
            'POST',
            $chatCompletionPath,
            $options
        );

        $decoded = $this->openRouterHelper->jsonDecode($response);

        if ($decoded === null) {
            return new ErrorData(
                code: 500,
                message: 'Empty response from OpenRouter API.',
            );
        }

        if (Arr::get($decoded, 'error')) {
            return new ErrorData(
                code: Arr::get($decoded, 'error.code', 500),
                message: Arr::get($decoded, 'error.message', 'Unknown error from OpenRouter API.'),
                metadata: Arr::get($decoded, 'error.metadata'),
            );
        }

        return $this->openRouterHelper->formChatResponse($decoded);
    }

    /**
     * Sends a streaming request for the given chat conversation.
     */
    public function chatStreamRequest(ChatData $chatData): PromiseInterface
    {
        // The path for the chat completion request.
        $chatCompletionPath = 'chat/completions';

        $chatData->stream = true;

        // Filter null values from the chatData object and return array.
        $chatData = $chatData->convertToArray();

        $headers = [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
        ];

        $options = [
            'json' => $chatData,
            'headers' => $headers,
            'stream' => true,
        ];

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
     * Sends a cost request for the given generation id.
     *
     * @throws ReflectionException|GuzzleException
     */
    public function costRequest(string $generationId): CostResponseData
    {
        // The path for the cost and stats request. e.g. generation?id=$GENERATION_ID
        $costPath = 'generation?id=' . $generationId;

        $response = app(ClientInterface::class)->request(
            'GET',
            $costPath
        );

        return $this->openRouterHelper->formCostsResponse($response);
    }

    /**
     * Sends limit request for the rate limit or credits left on an API key.
     *
     * @throws ReflectionException|GuzzleException
     */
    public function limitRequest(): LimitResponseData
    {
        // The path for the rate limit or credits left request.
        $limitPath = 'auth/key';

        $response = app(ClientInterface::class)->request(
            'GET',
            $limitPath
        );

        return $this->openRouterHelper->formLimitResponse($response);
    }

    /**
     * Filters streaming response string and maps it into an array of ResponseData.
     */
    public function filterStreamingResponse(string $streamingResponse): array
    {
        return $this->openRouterHelper->filterStreamingResponse($streamingResponse);
    }
}
