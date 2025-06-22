<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter;

use MoeMizrak\LaravelOpenrouter\Helpers\OpenRouterHelper;

/**
 * This abstract class forms the response from OpenRouter
 *
 * Class OpenRouterAPI
 *
 * @package MoeMizrak\LaravelOpenrouter
 */
abstract class OpenRouterAPI
{
    /**
     * RekognitionAPI constructor.
     *
     * @param OpenRouterHelper $openRouterHelper
     */
    public function __construct(
        protected OpenRouterHelper $openRouterHelper,
    ) {}
}