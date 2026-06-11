<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;

final class TextContentData extends DataTransferObject
{
    /**
     * The allowed type for content.
     */
    public const ALLOWED_TYPE = 'text';

    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * Type of the content. (i.e. text)
         */
        #[AllowedValues([self::ALLOWED_TYPE])]
        public string $type = self::ALLOWED_TYPE,

        /**
         * Text of the content.
         */
        public string $text,

        /**
         * Optional cache breakpoint for this content block.
         * Useful for explicit caching (e.g. caching a large reference text).
         *
         * Docs: https://openrouter.ai/docs/guides/best-practices/prompt-caching
         */
        public ?CacheControlData $cache_control = null,
    ) {
        parent::__construct(...func_get_args());
    }

    public function convertToArray(): array
    {
        return array_filter(
            [
                'type' => $this->type,
                'text' => $this->text,
                'cache_control' => $this->cache_control?->convertToArray(),
            ],
            fn($value) => $value !== null
        );
    }
}
