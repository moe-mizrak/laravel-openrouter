<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\LaravelData\Data as DataTransferObject;

/**
 * UsageData is the DTO for the usage info of the api call.
 *
 * Class UsageData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class UsageData extends DataTransferObject
{
    /**
     * UsageData constructor.
     *
     * @param int|null $prompt_tokens
     * @param int|null $completion_tokens
     * @param int|null $total_tokens
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
        public ?int $total_tokens = null
    ) {}
}