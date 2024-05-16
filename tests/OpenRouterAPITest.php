<?php

namespace MoeMizrak\LaravelOpenrouter\Tests;

use Illuminate\Support\Arr;
use MoeMizrak\LaravelOpenrouter\DTO\ChatData;
use MoeMizrak\LaravelOpenrouter\DTO\ImageContentPartData;
use MoeMizrak\LaravelOpenrouter\DTO\ImageUrlData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseFormatData;
use MoeMizrak\LaravelOpenrouter\DTO\TextContentData;
use MoeMizrak\LaravelOpenrouter\Exceptions\XorValidationException;
use MoeMizrak\LaravelOpenrouter\OpenRouterRequest;
use MoeMizrak\LaravelOpenrouter\Types\RoleType;

class OpenRouterAPITest extends TestCase
{
    private OpenRouterRequest $api;

    private string $model;
    private int $max_tokens;
    private string $content;
    private string $prompt;

    public function setUp(): void
    {
        parent::setUp();

        $this->content = 'Tell me a story about a rogue AI that falls in love with its creator.';
        $this->prompt = 'Why did the programmer go broke?';
        $this->model = 'mistralai/mistral-7b-instruct:free';
        $this->max_tokens = 100;

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
            'max_tokens' => $this->max_tokens,
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
            'max_tokens' => $this->max_tokens,
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
            'max_tokens' => $this->max_tokens,
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
            'max_tokens' => $this->max_tokens,
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
            'max_tokens' => $this->max_tokens,
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
            'max_tokens' => $this->max_tokens,
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
            'max_tokens' => $this->max_tokens,
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
            'max_tokens' => $this->max_tokens,
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
            'max_tokens' => $this->max_tokens,
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
            'max_tokens' => $this->max_tokens,
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

    //todo stream parameter should be tested
    // todo test $value instanceof DataTransferObject =>   #[AllowedValues(['none', 'auto'])]  public string|ToolCallData|null $tool_choice;
    // todo add test for $tool_choice in chatdata for dto object and others too, test validation class
    // todo add validation error case for tests, how to handle validation errors returned from spatie DTO, and even from api call error
}