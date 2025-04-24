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
         * @var int|null
         */
        public ?int $cost = null
    ) {
        parent::__construct(...func_get_args());
    }
}
