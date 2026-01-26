<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * PromptTokensDetailsData is the DTO for the detailed breakdown of prompt tokens.
 *
 * Class PromptTokensDetailsData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class PromptTokensDetailsData extends DataTransferObject
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * Number of cached tokens used in the prompt.
         *
         * @var int|null
         */
        public ?int $cached_tokens = null,

        /**
         * Number of tokens written to cache.
         *
         * @var int|null
         */
        public ?int $cache_write_tokens = null,

        /**
         * Number of audio tokens used in the prompt.
         *
         * @var int|null
         */
        public ?int $audio_tokens = null,

        /**
         * Number of video tokens used in the prompt.
         *
         * @var int|null
         */
        public ?int $video_tokens = null
    ) {
        parent::__construct(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_filter(
            [
                'cached_tokens'      => $this->cached_tokens,
                'cache_write_tokens' => $this->cache_write_tokens,
                'audio_tokens'       => $this->audio_tokens,
                'video_tokens'       => $this->video_tokens,
            ],
            fn($value) => $value !== null
        );
    }
}
