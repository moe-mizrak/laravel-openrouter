<?php

namespace MoeMizrak\LaravelOpenrouter\Tests;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;
use Mockery\MockInterface;
use MoeMizrak\LaravelOpenrouter\DTO\ChatData;
use MoeMizrak\LaravelOpenrouter\DTO\CostResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ImageContentPartData;
use MoeMizrak\LaravelOpenrouter\DTO\ImageUrlData;
use MoeMizrak\LaravelOpenrouter\DTO\LimitResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\MessageData;
use MoeMizrak\LaravelOpenrouter\DTO\ProviderPreferencesData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseFormatData;
use MoeMizrak\LaravelOpenrouter\DTO\TextContentData;
use MoeMizrak\LaravelOpenrouter\DTO\UsageData;
use MoeMizrak\LaravelOpenrouter\Exceptions\XorValidationException;
use MoeMizrak\LaravelOpenrouter\Facades\LaravelOpenRouter;
use MoeMizrak\LaravelOpenrouter\OpenRouterRequest;
use MoeMizrak\LaravelOpenrouter\Types\DataCollectionType;
use MoeMizrak\LaravelOpenrouter\Types\RoleType;
use MoeMizrak\LaravelOpenrouter\Types\RouteType;
use Spatie\DataTransferObject\Exceptions\ValidationException;

class OpenRouterAPITest extends TestCase
{
    private OpenRouterRequest $api;

    private string $model;
    private int $maxTokens;
    private string $content;
    private string $prompt;
    private MessageData $messageData;

    public function setUp(): void
    {
        parent::setUp();

        $this->content = 'Tell me a story about a rogue AI that falls in love with its creator.';
        $this->prompt = 'Why did the programmer go broke?';
        $this->model = 'mistralai/mistral-7b-instruct:free';
        $this->maxTokens = 100;
        $this->messageData = new MessageData([
            'content' => $this->content,
            'role' => RoleType::USER,
        ]);

        $this->api = $this->app->make(OpenRouterRequest::class);
    }

    private function mockBasicBody(): array
    {
        return [
            'id' => 'gen-QcWgjEtiEDNHgomV2jjoQpCZlkRZ',
            'provider' => 'HuggingFace',
            'model' => $this->model,
            'object' => 'chat.completion',
            'created' => 1718888436,
            'choices' => [
                [
                    'index' => 0,
                    'message' => [
                        'role' => RoleType::ASSISTANT,
                        'content' => 'Some random content',
                    ],
                    'finish_reason' => 'stop',
                ],
            ],
            'usage' => new UsageData([
                'prompt_tokens' => 23,
                'completion_tokens' => 100,
                'total_tokens' => 123,
            ]),
        ];
    }

    private function mockBasicCostBody(): array
    {
        return [
            'data' => [
                'id'                       => "gen-QcWgjEtiEDNHgomV2jjoQpCZlkRZ",
                'model'                    => $this->model,
                'total_cost'               => 0.00492,
                'streamed'                 => true,
                'origin'                   => "https://github.com/moe-mizrak/laravel-openrouter",
                'cancelled'                => false,
                'finish_reason'            => null, // Nullable field
                'generation_time'          => 0,
                'created_at'               => "2024-09-17T18:33:11.957775+00:00",
                'provider_name'            => "HuggingFace",
                'tokens_prompt'            => 24,
                'tokens_completion'        => 87,
                'native_tokens_prompt'     => 27,
                'native_tokens_completion' => 102,
                'num_media_prompt'         => null, // Nullable field
                'num_media_completion'     => null, // Nullable field
                'app_id'                   => 1777723,
                'latency'                  => 829,
                'moderation_latency'       => null, // Nullable field
                'upstream_id'              => null, // Nullable field
                'usage'                    => 0,
            ],
        ];
    }

    private function mockBasicLimitBody(): array
    {
        return [
            'data' => [
                'label'           => 'sk-or-v1-7a3...1f9',
                'usage'           => 7.2E-5,
                'limit'           => 1,
                'is_free_tier'    => true,
                'limit_remaining' => -0.0369027621,
                'rate_limit'      => [
                    'requests'  => 10,
                    'interval' => '10s',
                ],
            ],
        ];
    }

    private function mockOpenRouter(array $mockBody): void
    {
        $mockResponse = (new Response(200, [], json_encode($mockBody)));
        $this->mock(ClientInterface::class, function (MockInterface $mock) use ($mockResponse) {
            $mock->shouldReceive('request')
                ->once()
                ->andReturn($mockResponse);
        });
    }

    /**
     * General assertions required for testing instead of replicating the same code.
     *
     * @param $response
     * @return void
     */
    private function generalTestAssertions($response): void
    {
        $this->assertInstanceOf(ResponseData::class, $response);
        $this->assertNotNull($response->id);
        $this->assertEquals($this->model, $response->model);
        $this->assertEquals('chat.completion', $response->object);
        $this->assertNotNull($response->created);
        $this->assertNotNull($response->usage->prompt_tokens);
        $this->assertNotNull($response->usage->completion_tokens);
        $this->assertNotNull($response->usage->total_tokens);
        $this->assertNotNull($response->choices);
        $this->assertNotNull(Arr::get($response->choices[0], 'finish_reason'));
    }

    /**
     * @test
     */
    public function it_makes_a_basic_chat_completion_open_route_api_request()
    {
        /* SETUP */
        $chatData = new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
        $this->mockOpenRouter($this->mockBasicBody());

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->generalTestAssertions($response);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
    }

    /**
     * @test
     */
    public function it_makes_a_basic_chat_completion_open_route_api_request_with_historical_data()
    {
        /* SETUP */
        $firstMessage = new MessageData([
            'role' => RoleType::USER,
            'content' => 'My name is Moe, the AI necromancer.',
        ]);
        $chatData = new ChatData([
            'messages' => [
                $firstMessage,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
        $this->mockOpenRouter($this->mockBasicBody());
        $oldResponse = $this->api->chatRequest($chatData);
        $historicalMessage = new MessageData([
            'role'    => RoleType::ASSISTANT,
            'content' => Arr::get($oldResponse->choices[0],'message.content'),
        ]);
        $newMessage = new MessageData([
            'role' => RoleType::USER,
            'content' => 'Who am I?',
        ]);
        $chatData = new ChatData([
            'messages' => [
                $historicalMessage,
                $newMessage,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
        $mockBody = $this->mockBasicBody();
        $mockBody['choices'][0]['message.content'] = 'You are Moe the AI Necromancer, a friendly and knowledgeable assistant designed to help answer questions and engage in stimulating conversations. I specialize in a wide range of topics, including necromancy, AI, and many other subjects. How can I assist you today?';
        $this->mockOpenRouter($mockBody);

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->generalTestAssertions($response);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $content = Arr::get($response->choices[0], 'message.content');
        $this->assertTrue(str_contains($content, 'Moe'));
    }

    /**
     * @test
     */
    public function it_makes_a_basic_chat_completion_stream_request()
    {
        /* SETUP */
        $this->markTestSkipped('Test skipped until stream request is mocked');
        $chatData = new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);

        /* EXECUTE */
        $promise = $this->api->chatStreamRequest($chatData);

        /* ASSERT */
        $stream = $promise->wait();
        $rawResponse = $stream->read(1024);
        $response = LaravelOpenRouter::filterStreamingResponse($rawResponse);
        $this->assertNotNull($response);
        $this->assertIsArray($response);
        $this->assertEquals('chat.completion.chunk', $response[0]->object);
        $this->assertNotNull(Arr::get($response[0]->choices[0], 'delta'));
    }

    /**
     * @test
     */
    public function it_responds_error_data_when_stream_request_is_made_to_chat_completion_function()
    {
        /* SETUP */
        $chatData = new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
            'stream' => true,
        ]);

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->assertEquals(400, $response->code);
        $this->assertEquals('For stream chat completion please use "chatStreamRequest" method instead!', $response->message);
    }

    /**
     * @test
     */
    public function it_makes_a_basic_prompt_chat_completion_open_route_api_request()
    {
        /* SETUP */
        $chatData = new ChatData([
            'prompt' => $this->prompt,
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
        $mockBody = $this->mockBasicBody();
        $mockBody['choices'][0]['text'] = 'Some mocked text';
        $this->mockOpenRouter($mockBody);

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->generalTestAssertions($response);
        $this->assertNotNull(Arr::get($response->choices[0], 'text'));
    }

    /**
     * @test
     */
    public function it_throws_xor_validation_exception_when_both_message_and_prompt_empty_in_chat_data()
    {
        /* SETUP */
        $this->expectException(XorValidationException::class);

        /* EXECUTE */
        new ChatData([
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
    }

    /**
     * @test
     */
    public function it_throws_xor_validation_exception_when_both_message_and_prompt_are_provided()
    {
        /* SETUP */
        $this->expectException(XorValidationException::class);

        /* EXECUTE */
        new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'prompt' => $this->prompt,
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
    }

    /**
     * @test
     */
    public function it_successfully_sends_text_content_in_messages_in_the_open_route_api_request()
    {
        /* SETUP */
        $textContentData = new TextContentData([
            'type' => TextContentData::ALLOWED_TYPE, // it can only take text for text content
            'text' => $this->content,
        ]);
        $messageData = new MessageData([
            'role' => RoleType::USER, // text content is only for user role
            'content' => [
                $textContentData,
            ],
        ]);
        $chatData = new ChatData([
            'messages' => [
                $messageData,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
        $this->mockOpenRouter($this->mockBasicBody());

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->generalTestAssertions($response);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
    }

    /**
     * @test
     */
    public function it_successfully_sends_image_and_text_content_in_messages_in_the_open_route_api_request()
    {
        /* SETUP */
        $imageUrlData = new ImageUrlData([
            'url' => 'https://www.thewowstyle.com/wp-content/uploads/2015/01/images-of-nature-4.jpg',
            'detail' => 'Nature'
        ]);
        $imageContentPartData = new ImageContentPartData([
            'type'      => ImageContentPartData::ALLOWED_TYPE, // it can only take image_url for image content
            'image_url' => $imageUrlData,
        ]);
        $textContentData = new TextContentData([
            'type' => TextContentData::ALLOWED_TYPE, // it can only take text for text content
            'text' => 'what is in the image?',
        ]);
        $messageData = new MessageData([
            'role' => RoleType::USER, // image content is only for user role
            'content' => [
                $textContentData,
                $imageContentPartData,
            ],
        ]);
        $chatData = new ChatData([
            'messages' => [
                $messageData,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
        $this->mockOpenRouter($this->mockBasicBody());

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->generalTestAssertions($response);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
    }

    /**
     * @test
     */
    public function it_successfully_sends_multiple_text_content_in_messages_in_the_open_route_api_request()
    {
        /* SETUP */
        $textContentDataA = new TextContentData([
            'type' => TextContentData::ALLOWED_TYPE, // it can only take text for text content
            'text' => 'What is the result of 2+2?',
        ]);
        $textContentDataB = new TextContentData([
            'type' => TextContentData::ALLOWED_TYPE, // it can only take text for text content
            'text' => 'Now, multiply the result with 10.',
        ]);
        $messageData = new MessageData([
            'role' => RoleType::USER, // Text content is only for user role
            'content' => [
                $textContentDataA, // First text content
                $textContentDataB, // Second text content requires result from first content
            ],
        ]);
        $chatData = new ChatData([
            'messages' => [
                $messageData,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
        $this->mockOpenRouter($this->mockBasicBody());

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->generalTestAssertions($response);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
    }

    /**
     * @test
     * @deprecated - Please use if you set default model to a free one!
     */
    public function it_successfully_makes_a_basic_chat_completion_open_route_api_request_when_model_is_not_set()
    {
        /* SETUP */
        $this->markTestSkipped('This test method is deprecated because if the default model is set to a non-free model,
         it might call a paid one. For the sake of compatibility, this method will not be removed.');
        // model is not set, so open router will use user default model
        $chatData = new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'max_tokens' => $this->maxTokens,
        ]);

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->assertInstanceOf(ResponseData::class, $response);
        $this->assertNotNull($response->id);
        $this->assertNotEquals($this->model, $response->model); // Not equal to defined model, instead uses some other model as default
        $this->assertEquals('chat.completion', $response->object);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
    }

    /**
     * @test
     */
    public function it_makes_a_basic_chat_completion_open_route_api_request_with_response_format_json_schema()
    {
        /* SETUP */
        $responseFormatData = new ResponseFormatData([
            'type' => 'json_schema',
            'json_schema' => [
                'name' => 'content',
                'strict' => true,
                'schema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => [
                            'type' => 'string',
                            'description' => 'article title'
                        ],
                        'story' => [
                            'type' => 'string',
                            'description' => 'article content',
                        ]
                    ],
                    'required' => ['title', 'story'],
                    'additionalProperties' => false
                ]
            ],
        ]);
        $responseBody = [
            'id' => 'gen-QcWgjEtiEDNHgomV2jjoQpCZlkRZ',
            'provider' => 'HuggingFace',
            'model' => $this->model,
            'object' => 'chat.completion',
            'created' => 1718888436,
            'choices' => [
                [
                    'index' => 0,
                    'message' => [
                        'role' => RoleType::ASSISTANT,
                        'content' => '{
                             "title": "Sample name of the story",
                             "story": "Sample story"
                        }',
                    ],
                    'finish_reason' => 'stop',
                ],
            ],
            'usage' => new UsageData([
                'prompt_tokens' => 23,
                'completion_tokens' => 100,
                'total_tokens' => 123,
            ]),
        ];
        $provider = new ProviderPreferencesData([
            'require_parameters' => true,
        ]);
        $chatData = new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'model' => 'google/gemini-flash-1.5-exp',
            'max_tokens' => $this->maxTokens,
            'response_format' => $responseFormatData,
            'provider' => $provider,
        ]);
        $this->mockOpenRouter($responseBody);

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->generalTestAssertions($response);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
    }

    /**
     * @test
     */
    public function it_makes_a_basic_chat_completion_open_route_api_request_with_response_format()
    {
        /* SETUP */
        $responseFormatData = new ResponseFormatData([
            'type' => 'json_object'
        ]);
        $chatData = new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
            'response_format' => $responseFormatData,
        ]);
        $this->mockOpenRouter($this->mockBasicBody());

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->generalTestAssertions($response);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
    }

    /**
     * @test
     */
    public function it_makes_a_basic_chat_completion_open_route_api_request_with_stop_parameter()
    {
        /* SETUP */
        $stop = ['bugs'];
        $content = 'Repeat this sentence: Function junction, where parameters meet, variables mingle, and bugs retreat.';
        $messageData = new MessageData([
            'role' => RoleType::USER,
            'content' => $content,
        ]);
        $chatData = new ChatData([
            'messages' => [
                $messageData,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
            'stop' => $stop,
        ]);
        $mockBody = $this->mockBasicBody();
        $mockBody['choices'][0]['finish_reason'] = 'bugs';
        $this->mockOpenRouter($mockBody);

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->generalTestAssertions($response);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
        $this->assertEquals('bugs', Arr::get($response->choices[0], 'finish_reason'));
    }

    /**
     * @test
     */
    public function it_makes_cost_request_with_generation_id()
    {
        /* SETUP */
        $chatData = new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
        $this->mockOpenRouter($this->mockBasicBody());
        $chatResponse = $this->api->chatRequest($chatData);
        $generationId = $chatResponse->id;
        sleep(3); // Pauses the script for 3 seconds just to make sure $generationId is generated
        $this->mockOpenRouter($this->mockBasicCostBody());

        /* EXECUTE */
        $response = $this->api->costRequest($generationId);

        /* ASSERT */
        $this->assertInstanceOf(CostResponseData::class, $response);
        $this->assertNotNull($response->id);
        $this->assertEquals($this->model, $response->model);
        $this->assertNotNull($response->total_cost);
        $this->assertNotNull($response->origin);
        $this->assertNotNull($response->streamed);
        $this->assertNotNull($response->cancelled);
        $this->assertNotNull($response->generation_time);
        $this->assertNotNull($response->created_at);
        $this->assertNotNull($response->provider_name);
        $this->assertNotNull($response->tokens_prompt);
        $this->assertNotNull($response->tokens_completion);
        $this->assertNotNull($response->native_tokens_prompt);
        $this->assertNotNull($response->native_tokens_completion);
        $this->assertNotNull($response->app_id);
        $this->assertNotNull($response->latency);
        $this->assertNotNull($response->usage);
    }

    /**
     * @test
     */
    public function it_makes_chat_completion_api_request_with_llm_parameters()
    {
        /* SETUP */
        $maxTokens = 250;
        $temperature = 1.2;
        $topP = 0.7;
        $topK = 1.2;
        $frequencyPenalty = 2;
        $presencePenalty = 1.2;
        $repetitionPenalty = 1;
        $seed = 2;
        $chatData = new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'model' => $this->model,
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
            'top_p' => $topP,
            'top_k' => $topK,
            'frequency_penalty' => $frequencyPenalty,
            'presence_penalty' => $presencePenalty,
            'repetition_penalty' => $repetitionPenalty,
            'seed' => $seed,
        ]);
        $this->mockOpenRouter($this->mockBasicBody());

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->generalTestAssertions($response);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
    }

    /**
     * @test
     */
    public function it_makes_chat_completion_api_request_with_open_router_specific_parameters()
    {
        /* SETUP */
        $modelOpenchat = 'openchat/openchat-7b:free';
        $modelGryphe = 'gryphe/mythomist-7b:free';
        $transforms = ['middle-out']; // default for all models
        $models = [$this->model, $modelOpenchat, $modelGryphe];
        $route = RouteType::FALLBACK;
        $provider = new ProviderPreferencesData([
            'allow_fallbacks' => true,
            'require_parameters' => true,
            'data_collection' => DataCollectionType::ALLOW,
        ]);
        $chatData = new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'max_tokens' => $this->maxTokens,
            'transforms' => $transforms,
            'models' => $models,
            'route' => $route,
            'provider' => $provider,
        ]);
        $this->mockOpenRouter($this->mockBasicBody());

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->assertInstanceOf(ResponseData::class, $response);
        $this->assertNotNull($response->id);
        $this->assertEquals($this->model, $response->model); // Assert first model
        $this->assertEquals('chat.completion', $response->object);
        $this->assertNotNull($response->created);
        $this->assertNotNull($response->usage->prompt_tokens);
        $this->assertNotNull($response->usage->completion_tokens);
        $this->assertNotNull($response->usage->total_tokens);
        $this->assertNotNull($response->choices);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
    }

    /**
     * @test
     */
    public function it_makes_chat_completion_api_request_with_fallback_to_second_model_if_first_one_fails()
    {
        /* SETUP */
        $wrongModel = 'some/random/text:free';
        $modelGryphe = 'gryphe/mythomist-7b:free';
        $transforms = ['middle-out']; // default for all models
        $models = [$wrongModel, $modelGryphe, $this->model];
        $route = RouteType::FALLBACK;
        $provider = new ProviderPreferencesData([
            'allow_fallbacks' => true,
            'require_parameters' => true,
            'data_collection' => DataCollectionType::ALLOW,
        ]);
        $chatData = new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'max_tokens' => $this->maxTokens,
            'transforms' => $transforms,
            'models' => $models,
            'route' => $route,
            'provider' => $provider,
        ]);
        $mockBody = $this->mockBasicBody();
        $mockBody['model'] = $modelGryphe;
        $this->mockOpenRouter($mockBody);

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->assertInstanceOf(ResponseData::class, $response);
        $this->assertNotNull($response->id);
        $this->assertEquals($modelGryphe, $response->model); // Assert second model when first model fails
        $this->assertEquals('chat.completion', $response->object);
        $this->assertNotNull($response->created);
        $this->assertNotNull($response->usage->prompt_tokens);
        $this->assertNotNull($response->usage->completion_tokens);
        $this->assertNotNull($response->usage->total_tokens);
        $this->assertNotNull($response->choices);
        $this->assertNotNull(Arr::get($response->choices[0], 'finish_reason'));
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
    }

    /**
     * @test
     */
    public function it_throws_xor_validation_exception_when_both_model_and_models_empty_in_chat_data()
    {
        /* SETUP */
        $this->expectException(XorValidationException::class);

        /* EXECUTE */
        new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'max_tokens' => $this->maxTokens,
        ]);
    }

    /**
     * @test
     */
    public function it_throws_xor_validation_exception_when_both_model_and_models_are_provided()
    {
        /* SETUP */
        $modelGryphe = 'gryphe/mythomist-7b:free';
        $models = [$modelGryphe, $this->model];
        $this->expectException(XorValidationException::class);

        /* EXECUTE */
        new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'max_tokens' => $this->maxTokens,
            'model' => $this->model,
            'models' => $models,
        ]);
    }

    /**
     * @test
     */
    public function it_throws_validation_exception_when_NOT_ALLOWED_value_is_sent_for_route()
    {
        /* SETUP */
        $route = 'random'; // We have #[AllowedValues([RouteType::FALLBACK])]
        $this->expectException(ValidationException::class);

        /* EXECUTE */
        new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'max_tokens' => $this->maxTokens,
            'model' => $this->model,
            'route' => $route,
        ]);
    }

    /**
     * @test
     */
    public function it_throws_validation_exception_when_NOT_ALLOWED_value_is_sent_for_tool_choice()
    {
        /* SETUP */
        $toolChoice = 'random'; // We have #[AllowedValues([ToolChoiceType::AUTO, ToolChoiceType::NONE])]
        $this->expectException(ValidationException::class);

        /* EXECUTE */
        new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'max_tokens' => $this->maxTokens,
            'model' => $this->model,
            'tool_choice' => $toolChoice,
        ]);
    }

    /**
     * @test
     */
    public function it_makes_a_limit_open_route_api_request_and_gets_rate_limit_and_credit_left_on_api_key()
    {
        /* SETUP */
        $this->mockOpenRouter($this->mockBasicLimitBody());

        /* EXECUTE */
        $response = $this->api->limitRequest();

        /* ASSERT */
        $this->assertInstanceOf(LimitResponseData::class, $response);
        $this->assertNotNull($response->label);
        $this->assertNotNull($response->usage);
        $this->assertNotNull($response->is_free_tier);
        $this->assertNotNull($response->limit);
        $this->assertNotNull($response->limit_remaining);
        $this->assertNotNull($response->rate_limit);
        $this->assertNotNull($response->rate_limit->requests);
        $this->assertNotNull($response->rate_limit->interval);
    }

    /**
     * @test
     */
    public function it_makes_a_open_route_api_request_by_using_facade()
    {
        /* SETUP */
        $chatData = new ChatData([
            'messages' => [
                $this->messageData,
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
        $this->mockOpenRouter($this->mockBasicBody());

        /* EXECUTE */
        $response = LaravelOpenRouter::chatRequest($chatData);

        /* ASSERT */
        $this->generalTestAssertions($response);
    }
}