<?php

namespace MoeMizrak\LaravelOpenrouter\Facades;

use Illuminate\Support\Facades\Facade;
use MoeMizrak\LaravelOpenrouter\DTO\ChatData;
use MoeMizrak\LaravelOpenrouter\DTO\CostResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\LimitResponseData;
use MoeMizrak\LaravelOpenrouter\DTO\ResponseData;

/**
 * Facade for LaravelOpenRouter.
 *
 * @method static ResponseData chatRequest(ChatData $chatData) Sends a chat request to the OpenRouter API and returns the response data.
 * @method static CostResponseData costRequest(string $generationId) Sends a cost request to the OpenRouter API with the given generation ID and returns the cost response data.
 * @method static LimitResponseData limitRequest() Sends a limit request to the OpenRouter API and returns the limit response data.
 */
class LaravelOpenRouter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-openrouter';
    }
}