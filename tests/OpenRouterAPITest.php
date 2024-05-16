<?php

namespace MoeMizrak\LaravelOpenrouter\Tests;

use Illuminate\Support\Arr;
use MoeMizrak\LaravelOpenrouter\DTO\ChatData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseData;
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
            'prompt' => $this->prompt,
            'model' => $this->model,
            'max_tokens' => $this->max_tokens,
        ]);

        /* EXECUTE */
        $response = $this->api->chatRequest($chatData);

        /* ASSERT */
        $this->assertInstanceOf(ResponseData::class, $response);
        $this->assertNotNull($response->id);
        $this->assertEquals($this->model, $response->model);
        $this->assertEquals('chat.completion', $response->object);
        $this->assertNotNull($response->created);
        $this->assertNotNull($response->usage->prompt_tokens);
        $this->assertNotNull($response->usage->completion_tokens);
        $this->assertNotNull($response->usage->total_tokens);
        $this->assertNotNull($response->choices);
        $this->assertEquals(RoleType::ASSISTANT, Arr::get($response->choices[0], 'message.role'));
        $this->assertNotNull(Arr::get($response->choices[0], 'message.content'));
        $this->assertNotNull(Arr::get($response->choices[0], 'finish_reason'));
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
        $this->assertInstanceOf(ResponseData::class, $response);
        $this->assertNotNull($response->id);
        $this->assertEquals($this->model, $response->model);
        $this->assertEquals('chat.completion', $response->object);
        $this->assertNotNull($response->created);
        $this->assertNotNull($response->usage->prompt_tokens);
        $this->assertNotNull($response->usage->completion_tokens);
        $this->assertNotNull($response->usage->total_tokens);
        $this->assertNotNull($response->choices);
        $this->assertNotNull(Arr::get($response->choices[0], 'text'));
        $this->assertNotNull(Arr::get($response->choices[0], 'finish_reason'));
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

    // todo test $value instanceof DataTransferObject =>   #[AllowedValues(['none', 'auto'])]  public string|ToolCallData|null $tool_choice;
    // todo add test for $tool_choice in chatdata for dto object and others too, test validation class
    // todo add validation error case for tests, how to handle validation errors returned from spatie DTO, and even from api call error
}