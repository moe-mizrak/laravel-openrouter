
# Laravel OpenRouter

<br />

[![Latest Version on Packagist](https://img.shields.io/badge/packagist-v1.0-blue)](https://packagist.org/packages/moe-mizrak/laravel-openrouter)
[![](https://dcbadge.vercel.app/api/server/KBPhAPEJNj?style=flat)](https://discord.gg/3TbKAakhGb)
<br />

This Laravel package provides an easy-to-use interface for integrating **[OpenRouter](https://openrouter.ai/)** into your Laravel applications. **OpenRouter** is a unified interface for Large Language Models (LLMs) that allows you to interact with various **[AI models](https://openrouter.ai/docs#models)** through a single API.

## Table of Contents

- [🤖 Requirements](#-requirements)
- [🏁 Get Started](#-get-started)
- [⚙️ Configuration](#-configuration)
- [🎨 Usage](#-usage)
    - [Understanding ChatData DTO](#understanding-chatdata-dto)
        - [LLM Parameters](#llm-parameters)
        - [Function-calling](#function-calling)
        - [Additional Optional Parameters](#additional-optional-parameters)
        - [OpenRouter-only Parameters](#openrouter-only-parameters)
    - [Creating a ChatData Instance](#creating-a-chatdata-instance)
    - [Using Facade](#using-facade)
        - [Chat Request](#chat-request)
          - [Stream Chat Request](#stream-chat-request)
          - [Maintaining Conversation Continuity](#maintaining-conversation-continuity)
          - [Structured Output](#structured-output)
        - [Cost Request](#cost-request)
        - [Limit Request](#limit-request)
    - [Using OpenRouterRequest Class](#using-openrouterrequest-class)
        - [Chat Request](#chat-request-1)
        - [Cost Request](#cost-request-1)
        - [Limit Request](#limit-request-1)
- [💫 Contributing](#-contributing)
- [📜 License](#-license)

## 🤖 Requirements

- **PHP 8.1** or **higher**

## 🏁 Get Started

You can **install** the package via composer:
```bash
composer require moe-mizrak/laravel-openrouter
```

You can **publish** the **config file** with:
```bash
php artisan vendor:publish --tag=laravel-openrouter
```

This is the contents of the **published config file**:
```php
return [
    'api_endpoint' => env('OPENROUTER_API_ENDPOINT', 'https://openrouter.ai/api/v1/'),
    'api_key'      => env('OPENROUTER_API_KEY'),
    'api_timeout'  => env('OPENROUTER_API_TIMEOUT', 20)
];
```

## ⚙️ Configuration
After publishing the package configuration file, you'll need to add the following environment variables to your **.env** file:

```env
OPENROUTER_API_ENDPOINT=https://openrouter.ai/api/v1/
OPENROUTER_API_KEY=your_api_key
OPENROUTER_API_TIMEOUT=request_timeout
```

- OPENROUTER_API_ENDPOINT: The endpoint URL for the **OpenRouter API** (default: https://openrouter.ai/api/v1/).
- OPENROUTER_API_KEY: Your **API key** for accessing the OpenRouter API. You can obtain this key from the [OpenRouter dashboard](https://openrouter.ai/keys).
- OPENROUTER_API_TIMEOUT: Request timeout in seconds. Increase value to 120 - 180 if you use long-thinking models like openai/o1 (default: 20)

## 🎨 Usage
This package provides two ways to interact with the OpenRouter API: 
- Using the `LaravelOpenRouter` facade
- Instantiating the `OpenRouterRequest` class directly.

Both methods utilize the `ChatData` DTO class to structure the data sent to the API.
### Understanding ChatData DTO
The `ChatData` class is used to encapsulate the data required for making chat requests to the OpenRouter API. Here's a breakdown of the key properties:
- **messages** (array|null): An array of `MessageData` objects representing the chat messages. This field is XOR-gated with the `prompt` field.
- **prompt** (string|null): A string representing the prompt for the chat request. This field is XOR-gated with the `messages` field.
- **model** (string|null): The name of the model to be used for the chat request. If not specified, the user's default model will be used. This field is XOR-gated with the `models` field.
- **response_format** (ResponseFormatData|null): An instance of the `ResponseFormatData` class representing the desired format for the response.
- **stop** (array|string|null): A value specifying the stop sequence for the chat generation.
- **stream** (bool|null): A boolean indicating whether streaming should be enabled or not.
#### LLM Parameters
These properties control various aspects of the generated response (more [info](https://openrouter.ai/docs#parameters)):
- **max_tokens** (int|null): The maximum number of tokens that can be generated in the completion. Default is 1024.
- **temperature** (float|null): A value between 0 and 2 controlling the randomness of the output.
- **top_p** (float|null): A value between 0 and 1 for nucleus sampling, an alternative to temperature sampling.
- **top_k** (float|null): A value between 1 and infinity for top-k sampling (not available for OpenAI models).
- **frequency_penalty** (float|null): A value between -2 and 2 for penalizing new tokens based on their existing frequency.
- **presence_penalty** (float|null): A value between -2 and 2 for penalizing new tokens based on whether they appear in the text so far.
- **repetition_penalty** (float|null): A value between 0 and 2 for penalizing repetitive tokens.
- **seed** (int|null): A value for deterministic sampling (OpenAI models only, in beta).
#### Function-calling
Only natively suported by OpenAI models. For others, we submit a YAML-formatted string with these tools at the end of the prompt.
- **tool_choice** (string|array|null): A value specifying the tool choice for function calling (OpenAI models only).
- **tools** (array|null): An array of `ToolCallData` objects for function calling.
#### Additional optional parameters
- **logit_bias** (array|null): An array for modifying the likelihood of specified tokens appearing in the completion.
#### OpenRouter-only parameters
- **transforms** (array|null): An array for configuring prompt transforms.
- **models** (array|null): An array of models to automatically try if the primary model is unavailable. This field is XOR-gated with the `model` field.
- **route** (string|null): A value specifying the route type (e.g., `RouteType::FALLBACK`).
- **provider** (ProviderPreferencesData|null): An instance of the `ProviderPreferencesData` DTO object for configuring provider preferences.

### Creating a ChatData Instance
This is a sample chat data instance:
```php
$chatData = new ChatData([
    'messages' => [
        new MessageData([
            'role'    => RoleType::USER,
            'content' => [
                new TextContentData([
                    'type' => TextContentData::ALLOWED_TYPE,
                    'text' => 'This is a sample text content.',
                ]),
                new ImageContentPartData([
                    'type'      => ImageContentPartData::ALLOWED_TYPE,
                    'image_url' => new ImageUrlData([
                        'url'    => 'https://example.com/image.jpg',
                        'detail' => 'Sample image',
                    ]),
                ]),
            ],
        ]),
    ],
    'response_format' => new ResponseFormatData([
        'type' => 'json_object',
    ]),
    'stop' => ['stop_token'],
    'stream' => true,
    'max_tokens' => 1024,
    'temperature' => 0.7,
    'top_p' => 0.9,
    'top_k' => 50,
    'frequency_penalty' => 0.5,
    'presence_penalty' => 0.2,
    'repetition_penalty' => 1.2,
    'seed' => 42,
    'tool_choice' => 'auto',
    'tools' => [
        // ToolCallData instances
    ],
    'logit_bias' => [
        '50256' => -100,
    ],
    'transforms' => ['middle-out'],
    'models' => ['model1', 'model2'],
    'route' => RouteType::FALLBACK,
    'provider' => new ProviderPreferencesData([
        'allow_fallbacks'    => true,
        'require_parameters' => true,
        'data_collection'    => DataCollectionType::ALLOW,
    ]),
]);
```
### Using Facade
The `LaravelOpenRouter` facade offers a convenient way to make OpenRouter API requests.
#### Chat Request
To send a chat request, create an instance of `ChatData` and pass it to the `chatRequest` method:
```php
$content = 'Tell me a story about a rogue AI that falls in love with its creator.'; // Your desired prompt or content
$model = 'mistralai/mistral-7b-instruct:free'; // The OpenRouter model you want to use (https://openrouter.ai/docs#models)
$messageData = new MessageData([
    'content' => $content,
    'role'    => RoleType::USER,
]);

$chatData = new ChatData([
    'messages'   => [
        $messageData,
    ],
    'model'      => $model,
    'max_tokens' => 100, // Adjust this value as needed
]);

$chatResponse = LaravelOpenRouter::chatRequest($chatData);
```
- #### Stream Chat Request
Streaming chat request is also supported and can be used as following by using **chatStreamRequest** function:
```php
$content = 'Tell me a story about a rogue AI that falls in love with its creator.'; // Your desired prompt or content
$model = 'mistralai/mistral-7b-instruct:free'; // The OpenRouter model you want to use (https://openrouter.ai/docs#models)
$messageData = new MessageData([
    'content' => $content,
    'role'    => RoleType::USER,
]);

$chatData = new ChatData([
    'messages'   => [
        $messageData,
    ],
    'model'      => $model,
    'max_tokens' => 100, // Adjust this value as needed
]);

/*
 * Calls chatStreamRequest ($promise is type of PromiseInterface)
 */
$promise = LaravelOpenRouter::chatStreamRequest($chatData);

// Waits until the promise completes if possible.
$stream = $promise->wait(); // $stream is type of GuzzleHttp\Psr7\Stream

/*
 * 1) You can retrieve whole raw response as: - Choose 1) or 2) depending on your case.
 */
$rawResponseAll = $stream->getContents(); // Instead of chunking streamed response as below - while (! $stream->eof()), it waits and gets raw response all together.
$response = LaravelOpenRouter::filterStreamingResponse($rawResponseAll); // Optionally you can use filterStreamingResponse to filter raw streamed response, and map it into array of responseData DTO same as chatRequest response format.

// 2) Or Retrieve streamed raw response as it becomes available:
while (! $stream->eof()) {
    $rawResponse = $stream->read(1024); // readByte can be set as desired, for better performance 4096 byte (4kB) can be used.
    
    /*
     * Optionally you can use filterStreamingResponse to filter raw streamed response, and map it into array of responseData DTO same as chatRequest response format.
     */
    $response = LaravelOpenRouter::filterStreamingResponse($rawResponse);
}
```
You do **not** need to specify `'stream' = true` in ChatData since `chatStreamRequest` does it for you.
<details>

This is the expected sample rawResponse (raw response returned from OpenRouter stream chunk) `$rawResponse`:
```php
"""
: OPENROUTER PROCESSING\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":"Title"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":": Quant"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":"um Echo"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":": A Sym"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGG
"""

"""
IsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":"phony of Code"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":"\n\nIn"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":" the heart of"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":" the bustling"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistra
"""

"""
l-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":" city of Ne"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":"o-Tok"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":"yo, a"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718885921,"choices":[{"index":0,"delta":{"role":"assistant","content":" brilliant young research"},"finish_reason":null}]}\n
\n
data: {"id":"gen-eWgGaEbIzFq4ziGGIsIjyRtLda54","model":"mistralai/mistral-7b-instruct:free","object":"chat.com
"""
...

: OPENROUTER PROCESSING\n
\n
data: {"id":"gen-C6Xym94jZcvJv2vVpxYSyw2tV1fR","model":"mistralai/mistral-7b-instruct:free","object":"chat.completion.chunk","created":1718887189,"choices":[{"index":0,"delta":{"role":"assistant","content":""},"finish_reason":null}],"usage":{"prompt_tokens":23,"completion_tokens":100,"total_tokens":123}}\n
\n
data: [DONE]\n 
```
Last `data:` carries usage information of streaming.
`data: [DONE]\n` returned from OpenRouter server when streaming is over.

This is the sample response after filterStreamingResponse:
```
[
    ResponseData(
        id: "gen-QcWgjEtiEDNHgomV2jjoQpCZlkRZ",
        model: "mistralai/mistral-7b-instruct:free",
        object: "chat.completion.chunk",
        created: 1718888436,
        choices: [
            [
                "index" => 0,
                "delta" => [
                    "role" => "assistant",
                    "content" => "Title"
                ],
                "finish_reason" => null
            ]
        ],
        usage: null
    ), 
    ResponseData(
        id: "gen-QcWgjEtiEDNHgomV2jjoQpCZlkRZ",
        model: "mistralai/mistral-7b-instruct:free",
        object: "chat.completion.chunk",
        created: 1718888436,
        choices: [
            [
                "index" => 0,
                "delta" => [
                    "role" => "assistant",
                    "content" => "Quant"
                ],
                "finish_reason" => null
            ]
        ],
        usage: null
    ), 
    ...
    new ResponseData(
        id: 'gen-QcWgjEtiEDNHgomV2jjoQpCZlkRZ',
        model: 'mistralai/mistral-7b-instruct:free',
        object: 'chat.completion.chunk',
        created: 1718888436,
        choices: [
            [
                'index' => 0,
                'delta' => [
                    'role' => 'assistant',
                    'content' => '',
                ],
                'finish_reason' => null,
            ],
        ],
        usage: new UsageData(
            prompt_tokens: 23,
            completion_tokens: 100,
            total_tokens: 123,
        ),
    ),
]
```
</details>

- #### Maintaining Conversation Continuity
If you want to maintain **conversation continuity** meaning that historical chat will be remembered and considered for your new chat request, you need to send historical messages along with the new message:
```php
$model = 'mistralai/mistral-7b-instruct:free';
        
$firstMessage = new MessageData([
    'role'    => RoleType::USER,
    'content' => 'My name is Moe, the AI necromancer.',
]);
        
$chatData = new ChatData([
    'messages' => [
         $firstMessage,
     ],
     'model'   => $model,
]);
// This is the chat which you want LLM to remember
$oldResponse = LaravelOpenRouter::chatRequest($chatData);
        
/*
* You can skip part above and just create your historical message below (maybe you retrieve historical messages from DB etc.)
*/
        
// Here adding historical response to new message
$historicalMessage = new MessageData([
    'role'    => RoleType::ASSISTANT, // set as assistant since it is a historical message retrieved previously
    'content' => Arr::get($oldResponse->choices[0],'message.content'), // Historical response content retrieved from previous chat request
]);
// This is your new message
$newMessage = new MessageData([
    'role'    => RoleType::USER,
    'content' => 'Who am I?',
]);
        
$chatData = new ChatData([
    'messages' => [
         $historicalMessage,
         $newMessage,
    ],
    'model' => $model,
]);

$response = LaravelOpenRouter::chatRequest($chatData);
```
Expected response:
```php
$content = Arr::get($response->choices[0], 'message.content');
// content = You are Moe, a fictional character and AI Necromancer, as per the context of the conversation we've established. In reality, you are the user interacting with me, an assistant designed to help answer questions and engage in friendly conversation.
```

- #### Structured Output
(Please also refer to [OpenRouter Document Structured Output](https://openrouter.ai/docs/structured-outputs) for models supporting structured output, also for more details)

If you want to receive the response in a structured format, you can specify the `type` property for `response_format` (ResponseFormatData) as `json_object` in the `ChatData` object.

Additionally, it's recommended to set the `require_parameters` property for `provider` (ProviderPreferencesData) to `true` in the `ChatData` object.

```php
$chatData = new ChatData([
    'messages' => [
        new MessageData([
            'role'    => RoleType::USER,
            'content' => 'Tell me a story about a rogue AI that falls in love with its creator.',
        ]),
    ],
    'model'           => 'mistralai/mistral-7b-instruct:free',
    'response_format' => new ResponseFormatData([
        'type' => 'json_object',
    ]),
    'provider'        => new ProviderPreferencesData([
        'require_parameters' => true,
    ]),
]);
```

You can also specify the `response_format` as `json_schema` to receive the response in a specified schema format (Advisable to set `'strict' => true` in `json_schema` array for strict schema):
```php
$chatData = new ChatData([
    'messages'        => [
        new MessageData([
            'role'    => RoleType::USER,
            'content' => 'Tell me a story about a rogue AI that falls in love with its creator.',
        ]),
    ],
    'model'           => 'mistralai/mistral-7b-instruct:free',
    'response_format' => new ResponseFormatData([
        'type'   => 'json_schema',
        'json_schema' => [
            'name' => 'article',
            'strict' => true,
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'title' => [
                        'type' => 'string',
                        'description' => 'article title'
                    ],
                    'details' => [
                        'type' => 'string',
                        'description' => 'article detail'
                    ],
                    'keywords' => [
                        'type' => 'array',
                        'description' => 'article keywords',
                    ],
                ],
                'required' => ['title', 'details', 'keywords'],
                'additionalProperties' => false
            ]
        ],
    ]),
    'provider' => new ProviderPreferencesData([
        'require_parameters' => true,
    ]),
]);
```

#### Cost Request
To retrieve the cost of a generation, first make a `chat request` and obtain the `generationId`. Then, pass the generationId to the `costRequest` method:
```php
$content = 'Tell me a story about a rogue AI that falls in love with its creator.'; // Your desired prompt or content
$model = 'mistralai/mistral-7b-instruct:free'; // The OpenRouter model you want to use (https://openrouter.ai/docs#models)
$messageData = new MessageData([
    'content' => $content,
    'role'    => RoleType::USER,
]);

$chatData = new ChatData([
    'messages'   => [
        $messageData,
    ],
    'model'      => $model,
    'max_tokens' => 100, // Adjust this value as needed
]);

$chatResponse = LaravelOpenRouter::chatRequest($chatData);
$generationId = $chatResponse->id; // generation id which will be passed to costRequest

$costResponse = LaravelOpenRouter::costRequest($generationId);
```
#### Limit Request
To retrieve rate limit and credits left on the API key:
```php
$limitResponse = LaravelOpenRouter::limitRequest();
```

### Using OpenRouterRequest Class
You can also inject the `OpenRouterRequest` class in the **constructor** of your class and use its methods directly.
```php
public function __construct(protected OpenRouterRequest $openRouterRequest) {}
```
#### Chat Request
Similarly, to send a chat request, create an instance of `ChatData` and pass it to the `chatRequest` method:
```php
$content = 'Tell me a story about a rogue AI that falls in love with its creator.'; // Your desired prompt or content
$model = 'mistralai/mistral-7b-instruct:free'; // The OpenRouter model you want to use (https://openrouter.ai/docs#models)
$messageData = new MessageData([
    'content' => $content,
    'role'    => RoleType::USER,
]);

$chatData = new ChatData([
    'messages'   => [
        $messageData,
    ],
    'model'      => $model,
    'max_tokens' => 100, // Adjust this value as needed
]);

$response = $this->openRouterRequest->chatRequest($chatData);
```

#### Cost Request
Similarly, to retrieve the cost of a generation, create a `chat request` to obtain the `generationId`, then pass the `generationId` to the `costRequest` method:
```php
$content = 'Tell me a story about a rogue AI that falls in love with its creator.';
$model = 'mistralai/mistral-7b-instruct:free'; // The OpenRouter model you want to use (https://openrouter.ai/docs#models)
$messageData = new MessageData([
    'content' => $content,
    'role'    => RoleType::USER,
]);

$chatData = new ChatData([
    'messages'   => [
        $messageData,
    ],
    'model'      => $model,
    'max_tokens' => 100, // Adjust this value as needed
]);

$chatResponse = $this->openRouterRequest->chatRequest($chatData);
$generationId = $chatResponse->id; // generation id which will be passed to costRequest

$costResponse = $this->openRouterRequest->costRequest($generationId);
```
#### Limit Request
Similarly, to retrieve rate limit and credits left on the API key:
```php
$limitResponse = $this->openRouterRequest->limitRequest();
```

## 💫 Contributing

> **We welcome contributions!** If you'd like to improve this package, simply create a pull request with your changes. Your efforts help enhance its functionality and documentation.


## 📜 License
Laravel OpenRouter is an open-sourced software licensed under the **[MIT license](LICENSE)**.