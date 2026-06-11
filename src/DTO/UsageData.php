<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * UsageData is the DTO for the usage info of the api call.
 */
final class UsageData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * Equivalent to "native_tokens_completion" in the /generation API
         */
        public ?int $prompt_tokens = null,

        /**
         * Equivalent to "native_tokens_prompt"
         */
        public ?int $completion_tokens = null,

        /**
         * Sum of the above two fields ($prompt_tokens and $completion_tokens)
         */
        public ?int $total_tokens = null,

        /**
         * Credit usage of the request
         */
        public ?float $cost = null,

        /**
         * Detailed breakdown of prompt tokens (cached, audio, video)
         */
        public ?PromptTokensDetailsData $prompt_tokens_details = null,

        /**
         * Detailed breakdown of completion tokens (reasoning, image)
         */
        public ?CompletionTokensDetailsData $completion_tokens_details = null
    ) {
        parent::__construct(...func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_filter(
            [
                'prompt_tokens' => $this->prompt_tokens,
                'completion_tokens' => $this->completion_tokens,
                'total_tokens' => $this->total_tokens,
                'cost' => $this->cost,
                'prompt_tokens_details' => $this->prompt_tokens_details?->toArray(),
                'completion_tokens_details' => $this->completion_tokens_details?->toArray(),
            ],
            fn($value) => $value !== null
        );
    }
}
