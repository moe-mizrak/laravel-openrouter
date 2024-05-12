<?php

namespace MoeMizrak\LaravelOpenrouter;

use GuzzleHttp\Client;

/**
 * This abstract class forms the response from OpenRouter
 *
 * Class OpenRouterAPI
 * @package MoeMizrak\LaravelOpenrouter
 */
abstract class OpenRouterAPI
{
    /**
     * OpenRouterAPI constructor.
     * @param Client $client
     */
    public function __construct(protected Client $client) {}
}