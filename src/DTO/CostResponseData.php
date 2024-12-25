<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * CostResponseData is the response DTO for cost including token info and cost which consists of:
 *  - id
 *  - model
 *  - streamed
 *  - total_cost
 *  - origin
 *  - cancelled
 *  - finish_reason
 *  - generation_time
 *  - created_at
 *  - provider_name
 *  - tokens_prompt
 *  - tokens_completion
 *  - native_tokens_prompt
 *  - native_tokens_completion
 *  - num_media_prompt
 *  - num_media_completion
 *  - app_id
 *  - latency
 *  - moderation_latency
 *  - upstream_id
 *  - usage
 *
 * Class CostResponseData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class CostResponseData extends DataTransferObject
{
    /**
     * @param string $id
     * @param string $model
     * @param float $total_cost
     * @param string $origin
     * @param bool|null $streamed
     * @param bool|null $cancelled
     * @param string|null $finish_reason
     * @param int|null $generation_time
     * @param string $created_at
     * @param string|null $provider_name
     * @param int|null $tokens_prompt
     * @param int|null $tokens_completion
     * @param int|null $native_tokens_prompt
     * @param int|null $native_tokens_completion
     * @param int|null $num_media_prompt
     * @param int|null $num_media_completion
     * @param int|null $app_id
     * @param int|null $latency
     * @param int|null $moderation_latency
     * @param string|null $upstream_id
     * @param float|null $usage
     */
    public function __construct(
        /**
         * ID of the cost request
         *
         * @var string
         */
        public string $id,

        /**
         * Name of the model e.g. mistralai/mistral-7b-instruct:free
         *
         * @var string
         */
        public string $model,

        /**
         * Total cost of the request
         *
         * @var float
         */
        public float $total_cost,

        /**
         * Origin of the request
         *
         * @var string
         */
        public string $origin,

        /**
         * Whether the response was streamed
         *
         * @var bool|null
         */
        public ?bool $streamed = null,

        /**
         * Whether the request was cancelled
         *
         * @var bool|null
         */
        public ?bool $cancelled = null,

        /**
         * Reason for finishing the request
         *
         * @var string|null
         */
        public ?string $finish_reason = null,

        /**
         * Time taken for generation
         *
         * @var int|null
         */
        public ?int $generation_time = null,

        /**
         * Creation timestamp of the request
         *
         * @var string
         */
        public string $created_at,

        /**
         * Name of the provider
         *
         * @var string|null
         */
        public ?string $provider_name = null,

        /**
         * Number of tokens in the prompt
         *
         * @var int|null
         */
        public ?int $tokens_prompt = null,

        /**
         * Number of tokens in the completion
         *
         * @var int|null
         */
        public ?int $tokens_completion = null,

        /**
         * Number of native tokens in the prompt
         *
         * @var int|null
         */
        public ?int $native_tokens_prompt = null,

        /**
         * Number of native tokens in the completion
         *
         * @var int|null
         */
        public ?int $native_tokens_completion = null,

        /**
         * Number of media items in the prompt
         *
         * @var int|null
         */
        public ?int $num_media_prompt = null,

        /**
         * Number of media items in the completion
         *
         * @var int|null
         */
        public ?int $num_media_completion = null,

        /**
         * Application ID associated with the request
         *
         * @var int|null
         */
        public ?int $app_id = null,

        /**
         * Latency of the request in milliseconds
         *
         * @var int|null
         */
        public ?int $latency = null,

        /**
         * Moderation latency of the request in milliseconds
         *
         * @var int|null
         */
        public ?int $moderation_latency = null,

        /**
         * Upstream ID associated with the request
         *
         * @var string|null
         */
        public ?string $upstream_id = null,

        /**
         * Usage associated with the request
         *
         * @var float|null
         */
        public ?float $usage = null
    ) {
        parent::__construct(...func_get_args());
    }
}