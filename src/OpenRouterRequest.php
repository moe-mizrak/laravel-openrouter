<?php

namespace MoeMizrak\LaravelOpenrouter;

use GuzzleHttp\Exception\GuzzleException;
use MoeMizrak\LaravelOpenrouter\DTO\ChatData;
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

    // todo add other requests, e.g. /auth/keys, https://openrouter.ai/api/v1/generation?id=$GENERATION_ID
    // todo check for a request that gives the list of all available models

    /**
     * Sends a model request for the given chat conversation.
     *
     * @param ChatData $chatData
     * @return ResponseData
     *
     * @throws GuzzleException
     * @throws UnknownProperties
     */
    public function chatRequest(ChatData $chatData): ResponseData
    {
        // The path for the chat completion request.
        $chatCompletionPath = 'chat/completions';

        // Filter null values from the chatData object and return array.
        $chatData = $this->filterNullValuesRecursive($chatData);

        // Options for the Guzzle request
        $options = [
            'json' => ($chatData),
        ];

        // Send the request to the OpenRouter API chat completion endpoint and get the response.
        $response = $this->client->request(
            'POST',
            $chatCompletionPath,
            $options
        );

        return $this->formResponse($response);
    }

    /**
     * Forms the response as ResponseData including id, model, object created, choices and usage if exits.
     * First decodes the json response and get the result, then map it in ResponseData to return the response.
     *
     * @param ResponseInterface|null $response
     * @return ResponseData
     * @throws UnknownProperties
     */
    public function formResponse(?ResponseInterface $response = null) : ResponseData
    {
        // Get the response body or return null.
        $response = $response ? json_decode($response->getBody(), true) : null;

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
}