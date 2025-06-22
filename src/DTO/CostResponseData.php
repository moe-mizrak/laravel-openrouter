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
 *
 * Class CostResponseData
 *
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class CostResponseData extends DataTransferObject
{
    /**
     * @inheritDoc
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
         * Creation timestamp of the request
         *
         * @var string
         */
        public string $created_at,

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

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_filter(
            [
                'id'                       => $this->id,
                'model'                    => $this->model,
                'streamed'                 => $this->streamed,
                'total_cost'               => $this->total_cost,
                'origin'                   => $this->origin,
                'created_at'               => $this->created_at,
                'cancelled'                => $this->cancelled,
                'finish_reason'            => $this->finish_reason,
                'generation_time'          => $this->generation_time,
                'provider_name'            => $this->provider_name,
                'tokens_prompt'            => $this->tokens_prompt,
                'tokens_completion'        => $this->tokens_completion,
                'native_tokens_prompt'     => $this->native_tokens_prompt,
                'native_tokens_completion' => $this->native_tokens_completion,
                'num_media_prompt'         => $this->num_media_prompt,
                'num_media_completion'     => $this->num_media_completion,
                'app_id'                   => $this->app_id,
                'latency'                  => $this->latency,
                'moderation_latency'       => $this->moderation_latency,
                'upstream_id'              => $this->upstream_id,
                'usage'                    => $this->usage,
            ],
            fn($value) => $value !== null
        );
    }
}