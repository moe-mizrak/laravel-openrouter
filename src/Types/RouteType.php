<?php

namespace MoeMizrak\LaravelOpenrouter\Types;

/**
 * The models array, which lets you automatically try other models if the primary model's providers are down, rate-limited,
 * or refuse to reply due to content moderation required by all providers. So fallback comes handy for models that will be used if "route": "fallback".
 *
 * For more info: https://openrouter.ai/docs#model-routing
 *
 * Class RouteType
 * @package MoeMizrak\LaravelOpenrouter\Types
 */
class RouteType
{
    /**
     * Fallback model.
     *
     * @var string
     */
    const FALLBACK = 'fallback';
}