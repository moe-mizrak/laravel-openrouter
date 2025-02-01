<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenRouter API Key
    |--------------------------------------------------------------------------
    |
    | Here you may specify the API key for accessing the OpenRouter API.
    | This key is required to authenticate your requests to the OpenRouter service.
    | You can obtain your API key from the OpenRouter dashboard.
    |
    */
    'api_key' => env('OPENROUTER_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | OpenRouter API Endpoint
    |--------------------------------------------------------------------------
    |
    | Here you may specify the endpoint URL for the OpenRouter API.
    | This is the URL where your application will send requests to interact with OpenRouter.
    | You can find the API endpoint URL in the OpenRouter documentation.
    | Default value is https://openrouter.ai/api/v1/ , which is the base URL for all requests.
    */
    'api_endpoint' => env('OPENROUTER_API_ENDPOINT', 'https://openrouter.ai/api/v1/'),

    /*
    |--------------------------------------------------------------------------
    | OpenRouter Timeout
    |--------------------------------------------------------------------------
    |
    | Request timeout in seconds. Increase value to 120 - 180 if you use long-thinking models like openai/o1
    |
    */
    'api_timeout' => env('OPENROUTER_API_TIMEOUT', 20),

    /*
    |--------------------------------------------------------------------------
    | OpenRouter Title
    |--------------------------------------------------------------------------
    |
    | Title of your app to pass to openrouter
    |
    */
    'title' => env('OPENROUTER_API_TITLE', 'laravel-openrouter'),

    /*
    |--------------------------------------------------------------------------
    | OpenRouter Referer
    |--------------------------------------------------------------------------
    |
    | URL of your app to pass to openrouter
    |
    */
    'referer' => env('OPENROUTER_API_REFERER', 'https://github.com/moe-mizrak/laravel-openrouter'),
];
