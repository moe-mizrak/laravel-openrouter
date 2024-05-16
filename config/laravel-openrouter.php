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
    'api_key'      => env('OPENROUTER_API_KEY', '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'),

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
];