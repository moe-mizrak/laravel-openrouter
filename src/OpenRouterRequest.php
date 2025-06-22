<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use MoeMizrak\LaravelOpenrouter\DTO\ChatData;
use MoeMizrak\LaravelOpenrouter\DTO\CostResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ErrorData;
use MoeMizrak\LaravelOpenrouter\DTO\LimitResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseData;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;

/**
 * OpenRouter request and formed response class.
 *
 * OpenRouter doc: https://openrouter.ai/docs
 *
 * Class OpenRouterRequest
 * @package MoeMizrak\LaravelOpenrouter
 */
final class OpenRouterRequest extends OpenRouterAPI
{
    /**
     * Sends a model request for the given chat conversation.
     *
     * @param ChatData $chatData
     *
     * @return ErrorData|ResponseData
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

        // Send POST request to the OpenRouter API chat completion endpoint and get the response.
        $response = app(ClientInterface::class)->request(
            'POST',
            $chatCompletionPath,
            $options
        );

        // Decode the json response
        $response = $this->openRouterHelper->jsonDecode($response);

        return $this->openRouterHelper->formChatResponse($response);
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
        $chatData = $chatData->convertToArray();

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
     * Sends a cost request for the given generation id.
     *
     * @param string $generationId
     *
     * @return CostResponseData
     * @throws ReflectionException|GuzzleException
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

        return $this->openRouterHelper->formCostsResponse($response);
    }

    /**
     * Sends limit request for the rate limit or credits left on an API key.
     *
     * @return LimitResponseData
     * @throws ReflectionException|GuzzleException
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

        return $this->openRouterHelper->formLimitResponse($response);
    }
}
