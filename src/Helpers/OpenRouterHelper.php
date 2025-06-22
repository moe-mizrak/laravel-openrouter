<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Helpers;

use Illuminate\Support\Arr;
use JsonException;
use MoeMizrak\LaravelOpenrouter\DTO\CostResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\LimitResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\RateLimitData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\UsageData;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;

/**
 * OpenRouter helper class is responsible for providing helper methods such as forming responses, decoding JSON, filtering stream responses and so on.
 *
 * @class OpenRouterHelper
 */
final class OpenRouterHelper
{
    // Buffer variable for incomplete streaming data.
    private static string $buffer = '';

    /**
     * Forms the response as ResponseData including id, model, object created, choices and usage if exits.
     *
     * @param mixed|null $response
     *
     * @return ResponseData
     * @throws ReflectionException
     */
    public function formChatResponse(mixed $response = null): ResponseData
    {
        // Map the usage data if it exists.
        $usageArray = Arr::get($response, 'usage');
        $usage = new UsageData(
            prompt_tokens: Arr::get($usageArray, 'prompt_tokens'),
            completion_tokens: Arr::get($usageArray, 'completion_tokens'),
            total_tokens: Arr::get($usageArray, 'total_tokens'),
            cost: Arr::get($usageArray, 'cost'),
        );

        // Map the response data to ResponseData and return it.
        return new ResponseData(
            id: Arr::get($response, 'id'),
            model: Arr::get($response, 'model'),
            object: Arr::get($response, 'object'),
            created: Arr::get($response, 'created'),
            provider: Arr::get($response, 'provider'),
            citations: Arr::get($response, 'citations'),
            choices: Arr::get($response, 'choices'),
            usage: $usage,
        );
    }

    /**
     * Forms the cost response as CostResponseData.
     * First decodes the json response, then map it in CostResponseData to return the response.
     *
     * @param ResponseInterface|null $response
     *
     * @return CostResponseData
     * @throws ReflectionException
     */
    public function formCostsResponse(?ResponseInterface $response = null): CostResponseData
    {
        // Decode the json response
        $response = $this->jsonDecode($response);

        // Map the response data to CostResponseData and return it.
        return new CostResponseData(
            id: Arr::get($response, 'data.id'),
            model: Arr::get($response, 'data.model'),
            total_cost: Arr::get($response, 'data.total_cost'),
            origin: Arr::get($response, 'data.origin'),
            created_at: Arr::get($response, 'data.created_at'),
            streamed: Arr::get($response, 'data.streamed'),
            cancelled: Arr::get($response, 'data.cancelled'),
            finish_reason: Arr::get($response, 'data.finish_reason'),
            generation_time: Arr::get($response, 'data.generation_time'),
            provider_name: Arr::get($response, 'data.provider_name'),
            tokens_prompt: Arr::get($response, 'data.tokens_prompt'),
            tokens_completion: Arr::get($response, 'data.tokens_completion'),
            native_tokens_prompt: Arr::get($response, 'data.native_tokens_prompt'),
            native_tokens_completion: Arr::get($response, 'data.native_tokens_completion'),
            num_media_prompt: Arr::get($response, 'data.num_media_prompt'),
            num_media_completion: Arr::get($response, 'data.num_media_completion'),
            app_id: Arr::get($response, 'data.app_id'),
            latency: Arr::get($response, 'data.latency'),
            moderation_latency: Arr::get($response, 'data.moderation_latency'),
            upstream_id: Arr::get($response, 'data.upstream_id'),
            usage: Arr::get($response, 'data.usage'),
        );
    }

    /**
     * Forms the response as LimitResponseData
     * First decodes the json response and get the result, then map it in LimitResponseData to return the response.
     *
     * @param ResponseInterface|null $response
     *
     * @return LimitResponseData
     * @throws ReflectionException
     */
    public function formLimitResponse(?ResponseInterface $response = null): LimitResponseData
    {
        // Decode the json response
        $response = $this->jsonDecode($response);

        // Map the rate limit data if it exists.
        $rateLimitArray = Arr::get($response, 'data.rate_limit');
        $rateLimit = new RateLimitData(
            requests: Arr::get($rateLimitArray, 'requests'),
            interval: Arr::get($rateLimitArray, 'interval'),
        );

        // Map the response data to LimitResponseData and return it.
        return new LimitResponseData(
            label: Arr::get($response, 'data.label'),
            usage: Arr::get($response, 'data.usage'),
            limit_remaining: Arr::get($response, 'data.limit_remaining'),
            limit: Arr::get($response, 'data.limit'),
            is_free_tier: Arr::get($response, 'data.is_free_tier'),
            rate_limit: $rateLimit,
        );
    }

    /**
     * Decodes response to json.
     *
     * @param ResponseInterface|null $response
     *
     * @return mixed|null
     */
    public function jsonDecode(?ResponseInterface $response = null): mixed
    {
        // Get the response body or return null.
        return ($response ? json_decode((string) $response->getBody(), true) : null);
    }

    /**
     * It filters streaming response string so that response string is mapped into ResponseData.
     *
     * @param string $streamingResponse
     *
     * @return array
     * @throws ReflectionException
     */
    public function filterStreamingResponse(string $streamingResponse): array
    {
        // Prepend any leftover data from the previous iteration
        $streamingResponse = self::$buffer . $streamingResponse;
        // Clear buffer
        self::$buffer = '';

        // Split the string by lines
        $lines = explode("\n", $streamingResponse);

        // Filter out unnecessary lines and decode the JSON data
        $responseDataArray = [];

        // Flag to indicate if the first line is a complete JSON
        $firstLineComplete = false;

        foreach ($lines as $line) {
            if (str_starts_with($line, 'data: ')) {
                // Remove "data: " prefix
                $jsonData = substr($line, strlen('data: '));

                try {
                    // Attempt to decode the JSON data
                    $data = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
                    $responseDataArray[] = $this->formChatResponse($data);
                    $firstLineComplete = true;
                } catch (JsonException $e) {
                    // If JSON decoding fails, buffer the line and continue
                    self::$buffer = $line;
                    continue;
                }
            } else if (trim($line) === '' && ! empty(self::$buffer)) {
                // If the line is empty and there's something in the buffer, try to process the buffer
                try {
                    // Attempt to decode the JSON data
                    $data = json_decode(self::$buffer, true, 512, JSON_THROW_ON_ERROR);
                    $responseDataArray[] = $this->formChatResponse($data);
                    self::$buffer = ''; // Clear buffer after successful processing
                } catch (JsonException $e) {
                    // If JSON decoding fails, retain the buffer for next iteration
                    continue;
                }
            } else if (! str_starts_with($line, 'data: ') && ! empty(trim($line))) {
                // If the line doesn't start with 'data: ', it might be part of a multiline JSON or a partial line
                if (! $firstLineComplete) {
                    // If it's the first line and not complete, assume it's part of the first JSON object
                    self::$buffer = $line;
                    $firstLineComplete = true; // Set flag to true after buffering incomplete first line
                } else {
                    // If it's not the first line or the first line is complete, buffer it for next iteration
                    self::$buffer .= $line;
                }
            } else {
                // Line does not contain 'data: ' and is not part of a multiline JSON, likely incomplete
                self::$buffer .= $line;
            }
        }

        return $responseDataArray;
    }
}