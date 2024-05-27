<?php

namespace MoeMizrak\LaravelOpenrouter\Tests;

use Illuminate\Support\Arr;
use MoeMizrak\LaravelOpenrouter\DTO\ChatData;
use MoeMizrak\LaravelOpenrouter\DTO\CostResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ImageContentPartData;
use MoeMizrak\LaravelOpenrouter\DTO\ImageUrlData;
use MoeMizrak\LaravelOpenrouter\DTO\LimitResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ProviderPreferencesData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseFormatData;
use MoeMizrak\LaravelOpenrouter\DTO\TextContentData;
use MoeMizrak\LaravelOpenrouter\Exceptions\XorValidationException;
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

    public function setUp(): void
    {
        parent::setUp();

        $this->content = 'Tell me a story about a rogue AI that falls in love with its creator.';
        $this->prompt = 'Why did the programmer go broke?';
        $this->model = 'mistralai/mistral-7b-instruct:free';
        $this->maxTokens = 100;

        $this->api = $this->app->make(OpenRouterRequest::class);
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
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);

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
    public function it_makes_a_basic_prompt_chat_completion_open_route_api_request()
    {
        /* SETUP */
        $chatData = new ChatData([
            'prompt' => $this->prompt,
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);

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
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
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
        $chatData = new ChatData([
            'messages' => [
                [
                    'role' => RoleType::USER, // text content is only for user role
                    'content' => [$textContentData], // will be an array of text content data (it can take string, array or null)
                ],
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);

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
        $chatData = new ChatData([
            'messages' => [
                [
                    'role' => RoleType::USER, // image content is only for user role
                    'content' => [
                        $textContentData,
                        $imageContentPartData,
                    ],
                ],
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);

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
        $chatData = new ChatData([
            'messages' => [
                [
                    'role' => RoleType::USER, // Text content is only for user role
                    'content' => [
                        $textContentDataA, // First text content
                        $textContentDataB, // Second text content requires result from first content
                    ],
                ],
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);

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
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
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
    public function it_makes_a_basic_chat_completion_open_route_api_request_with_response_format()
    {
        /* SETUP */
        $responseFormatData = new ResponseFormatData([
            'type' => 'json_object'
        ]);
        $chatData = new ChatData([
            'messages' => [
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
            'response_format' => $responseFormatData,
        ]);

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
        $chatData = new ChatData([
            'messages' => [
                [
                    'role' => RoleType::USER,
                    'content' => $content,
                ],
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
            'stop' => $stop,
        ]);

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
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
            ],
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
        ]);
        $chatResponse = $this->api->chatRequest($chatData);
        $generationId = $chatResponse->id;
        sleep(3); // Pauses the script for 3 seconds just to make sure $generationId is generated

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
        $this->assertNotNull($response->finish_reason);
        $this->assertNotNull($response->generation_time);
        $this->assertNotNull($response->created_at);
        $this->assertNotNull($response->provider_name);
        $this->assertNotNull($response->tokens_prompt);
        $this->assertNotNull($response->tokens_completion);
        $this->assertNotNull($response->native_tokens_prompt);
        $this->assertNotNull($response->native_tokens_completion);
        $this->assertNotNull($response->app_id);
        $this->assertNotNull($response->latency);
        $this->assertNotNull($response->upstream_id);
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
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
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
        $models = [$modelOpenchat, $modelGryphe, $this->model];
        $route = RouteType::FALLBACK;
        $provider = new ProviderPreferencesData([
            'allow_fallbacks' => true,
            'require_parameters' => true,
            'data_collection' => DataCollectionType::ALLOW,
        ]);
        $chatData = new ChatData([
            'messages' => [
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
            ],
            'max_tokens' => $this->maxTokens,
            'transforms' => $transforms,
            'models' => $models,
            'route' => $route,
            'provider' => $provider,
        ]);

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->assertInstanceOf(ResponseData::class, $response);
        $this->assertNotNull($response->id);
        $this->assertEquals($modelOpenchat, $response->model); // Assert first model
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
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
            ],
            'max_tokens' => $this->maxTokens,
            'transforms' => $transforms,
            'models' => $models,
            'route' => $route,
            'provider' => $provider,
        ]);

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
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
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
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
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
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
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
                [
                    'role' => RoleType::USER,
                    'content' => $this->content,
                ],
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
        /* EXECUTE */
        $response = $this->api->limitRequest();

        /* ASSERT */
        $this->assertInstanceOf(LimitResponseData::class, $response);
        $this->assertNotNull($response->label);
        $this->assertNotNull($response->usage);
        $this->assertNotNull($response->is_free_tier);
        $this->assertNotNull($response->rate_limit);
        $this->assertNotNull($response->rate_limit->requests);
        $this->assertNotNull($response->rate_limit->interval);
    }
}