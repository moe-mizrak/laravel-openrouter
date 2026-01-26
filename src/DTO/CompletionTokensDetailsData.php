<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * CompletionTokensDetailsData is the DTO for the detailed breakdown of completion tokens.
 *
 * Class CompletionTokensDetailsData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class CompletionTokensDetailsData extends DataTransferObject
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * Number of reasoning tokens used in the completion.
         *
         * @var int|null
         */
        public ?int $reasoning_tokens = null,

        /**
         * Number of audio tokens used in the completion.
         *
         * @var int|null
         */
        public ?int $audio_tokens = null,

        /**
         * Number of image tokens used in the completion.
         *
         * @var int|null
         */
        public ?int $image_tokens = null,

        /**
         * Number of accepted prediction tokens.
         *
         * @var int|null
         */
        public ?int $accepted_prediction_tokens = null,

        /**
         * Number of rejected prediction tokens.
         *
         * @var int|null
         */
        public ?int $rejected_prediction_tokens = null
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
                'reasoning_tokens'           => $this->reasoning_tokens,
                'audio_tokens'               => $this->audio_tokens,
                'image_tokens'               => $this->image_tokens,
                'accepted_prediction_tokens' => $this->accepted_prediction_tokens,
                'rejected_prediction_tokens' => $this->rejected_prediction_tokens,
            ],
            fn($value) => $value !== null
        );
    }
}
