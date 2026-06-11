<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * CostResponseData is the response DTO for cost including token info and cost which consists of:
 *  - id
 *  - model
 *  - streamed
 *  - total_cost
 *  - origin
 *  - created_at
 *  - cancelled
 *  - finish_reason
 *  - generation_time
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
 */
final class CostResponseData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * ID of the cost request
         */
        public string $id,

        /**
         * Name of the model e.g. mistralai/mistral-7b-instruct:free
         */
        public string $model,

        /**
         * Total cost of the request
         */
        public float $total_cost,

        /**
         * Origin of the request
         */
        public string $origin,

        /**
         * Creation timestamp of the request
         */
        public string $created_at,

        /**
         * Whether the response was streamed
         */
        public ?bool $streamed = null,

        /**
         * Whether the request was cancelled
         */
        public ?bool $cancelled = null,

        /**
         * Reason for finishing the request
         */
        public ?string $finish_reason = null,

        /**
         * Time taken for generation
         */
        public ?int $generation_time = null,

        /**
         * Name of the provider
         */
        public ?string $provider_name = null,

        /**
         * Number of tokens in the prompt
         */
        public ?int $tokens_prompt = null,

        /**
         * Number of tokens in the completion
         */
        public ?int $tokens_completion = null,

        /**
         * Number of native tokens in the prompt
         */
        public ?int $native_tokens_prompt = null,

        /**
         * Number of native tokens in the completion
         */
        public ?int $native_tokens_completion = null,

        /**
         * Number of media items in the prompt
         */
        public ?int $num_media_prompt = null,

        /**
         * Number of media items in the completion
         */
        public ?int $num_media_completion = null,

        /**
         * Application ID associated with the request
         */
        public ?int $app_id = null,

        /**
         * Latency of the request in milliseconds
         */
        public ?int $latency = null,

        /**
         * Moderation latency of the request in milliseconds
         */
        public ?int $moderation_latency = null,

        /**
         * Upstream ID associated with the request
         */
        public ?string $upstream_id = null,

        /**
         * Usage associated with the request
         */
        public ?float $usage = null
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
                'id' => $this->id,
                'model' => $this->model,
                'streamed' => $this->streamed,
                'total_cost' => $this->total_cost,
                'origin' => $this->origin,
                'created_at' => $this->created_at,
                'cancelled' => $this->cancelled,
                'finish_reason' => $this->finish_reason,
                'generation_time' => $this->generation_time,
                'provider_name' => $this->provider_name,
                'tokens_prompt' => $this->tokens_prompt,
                'tokens_completion' => $this->tokens_completion,
                'native_tokens_prompt' => $this->native_tokens_prompt,
                'native_tokens_completion' => $this->native_tokens_completion,
                'num_media_prompt' => $this->num_media_prompt,
                'num_media_completion' => $this->num_media_completion,
                'app_id' => $this->app_id,
                'latency' => $this->latency,
                'moderation_latency' => $this->moderation_latency,
                'upstream_id' => $this->upstream_id,
                'usage' => $this->usage,
            ],
            fn($value) => $value !== null
        );
    }
}
