<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;

/**
 * DTO for the contents.
 *
 * Class TextContentData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class TextContentData extends DataTransferObject
{
    /**
     * The allowed type for content.
     */
    public const ALLOWED_TYPE = 'text';

    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * Type of the content. (i.e. text)
         *
         * @var string
         */
        #[AllowedValues([self::ALLOWED_TYPE])]
        public string $type = self::ALLOWED_TYPE,

        /**
         * Text of the content.
         *
         * @var string
         */
        public string $text,

        /**
         * Optional cache breakpoint for this content block.
         * Useful for explicit caching (e.g. caching a large reference text).
         *
         * Docs: https://openrouter.ai/docs/guides/best-practices/prompt-caching
         *
         * @var CacheControlData|null
         */
        public ?CacheControlData $cache_control = null,
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
                'text' => $this->text,
                'cache_control' => $this->cache_control?->convertToArray(),
            ],
            fn($value) => $value !== null
        );
    }
}