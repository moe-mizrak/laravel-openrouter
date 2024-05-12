<?php

namespace MoeMizrak\LaravelOpenrouter;

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

    // todo testing open router request
    public function testRequest()
    {
        // TODO: options for post request will be added here for testing the request
        return $this->client->post('');
    }
}