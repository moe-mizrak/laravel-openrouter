<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * UsageData is the DTO for the usage info of the api call.
 *
 * Class UsageData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class UsageData extends DataTransferObject
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * Equivalent to "native_tokens_completion" in the /generation API
         *
         * @var int|null
         */
        public ?int $prompt_tokens = null,

        /**
         * Equivalent to "native_tokens_prompt"
         *
         * @var int|null
         */
        public ?int $completion_tokens = null,

        /**
         * Sum of the above two fields ($prompt_tokens and $completion_tokens)
         *
         * @var int|null
         */
        public ?int $total_tokens = null,

        /**
         * Credit usage of the request
         *
         * @var float|null
         */
        public ?float $cost = null
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
                'prompt_tokens'     => $this->prompt_tokens,
                'completion_tokens' => $this->completion_tokens,
                'total_tokens'      => $this->total_tokens,
                'cost'              => $this->cost,
            ],
            fn($value) => $value !== null
        );
    }
}
