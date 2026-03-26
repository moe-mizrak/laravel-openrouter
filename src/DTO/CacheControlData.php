<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;

/**
 * DTO for the prompt caching control.
 *
 * Docs: https://openrouter.ai/docs/guides/best-practices/prompt-caching
 *
 * Class CacheControlData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class CacheControlData extends DataTransferObject
{
    /**
     * The allowed cache_control type value.
     */
    public const ALLOWED_TYPE = 'ephemeral';

    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * Cache control type. Currently OpenRouter documents "ephemeral".
         *
         * @var string
         */
        #[AllowedValues([self::ALLOWED_TYPE])]
        public string $type = self::ALLOWED_TYPE,

        /**
         * Optional TTL for cache entry.
         * Example: "1h"
         *
         * @var string|null
         */
        public ?string $ttl = null,
    ) {
        parent::__construct(...func_get_args());
    }

    /**
     * @return array
     */
    public function convertToArray(): array
    {
        return array_filter(
            [
                'type' => $this->type,
                'ttl'  => $this->ttl,
            ],
            fn($value) => $value !== null
        );
    }
}