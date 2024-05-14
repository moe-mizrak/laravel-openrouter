<?php

namespace MoeMizrak\LaravelOpenrouter;

use Psr\Http\Message\ResponseInterface;

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
    // todo add method for getting the models, from the dto object or from the open router api

    // todo add validation for payload objects and handle the error messages,
    // .. todo you can use Spatie DTO validation as in AllowedValues https://github.com/moe-mizrak/Validation

    // todo testing open router request
    public function testRequest()
    {
        // JSON payload to be sent in the request body
        $jsonPayload = [
            'model' => 'mistralai/mistral-7b-instruct:free', // todo will be dynamic, retrive them from api call
            'max_tokens' => 1024, // todo will be dynamic? if not set a value for covering most cases
            'messages' => [
                ['role' => 'user', 'content' => 'Hello, world'] // todo role and content should not be static
            ]
        ];

        // Options for the Guzzle request
        $options = [
            'json' => $jsonPayload, // Set the request body as JSON
        ];

        $response = $this->client->post('', $options);

        return $this->formResponse($response);
    }

    public function formResponse(?ResponseInterface $response = null)
    {
        $response = $response ? json_decode($response->getBody(), true) : null;

        // todo dtos will be set here to the response

        return $response;
    }
}