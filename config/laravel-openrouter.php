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
    'api_key'      => env('OPENROUTER_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | OpenRouter API Endpoint
    |--------------------------------------------------------------------------
    |
    | Here you may specify the endpoint URL for the OpenRouter API.
    | This is the URL where your application will send requests to interact with OpenRouter.
    | You can find the API endpoint URL in the OpenRouter documentation.
    | todo make url in a way that for chat completion it will be different, for checking rate limit, balance https://openrouter.ai/api/v1/auth/key
    | .. todo here only main url will be set which is https://openrouter.ai/api/v1/  , so based on the req rest will be added
    */
    'api_endpoint' => env('OPENROUTER_API_ENDPOINT'),
];