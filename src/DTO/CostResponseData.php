<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\DataTransferObject\DataTransferObject;

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
     * ID of the cost request
     *
     * @var string
     */
    public string $id;

    /**
     * Name of the model e.g. mistralai/mistral-7b-instruct:free
     *
     * @var string
     */
    public string $model;

    /**
     * Total cost of the request
     *
     * @var float
     */
    public float $total_cost;

    /**
     * Origin of the request
     *
     * @var string
     */
    public string $origin;

    /**
     * Whether the response was streamed
     *
     * @var bool|null
     */
    public ?bool $streamed;

    /**
     * Whether the request was cancelled
     *
     * @var bool|null
     */
    public ?bool $cancelled;

    /**
     * Reason for finishing the request
     *
     * @var string|null
     */
    public ?string $finish_reason;

    /**
     * Time taken for generation
     *
     * @var int|null
     */
    public ?int $generation_time;

    /**
     * Creation timestamp of the request
     *
     * @var string
     */
    public string $created_at;

    /**
     * Name of the provider
     *
     * @var string|null
     */
    public ?string $provider_name;

    /**
     * Number of tokens in the prompt
     *
     * @var int|null
     */
    public ?int $tokens_prompt;

    /**
     * Number of tokens in the completion
     *
     * @var int|null
     */
    public ?int $tokens_completion;

    /**
     * Number of native tokens in the prompt
     *
     * @var int|null
     */
    public ?int $native_tokens_prompt;

    /**
     * Number of native tokens in the completion
     *
     * @var int|null
     */
    public ?int $native_tokens_completion;

    /**
     * Number of media items in the prompt
     *
     * @var int|null
     */
    public ?int $num_media_prompt;

    /**
     * Number of media items in the completion
     *
     * @var int|null
     */
    public ?int $num_media_completion;

    /**
     * Application ID associated with the request
     *
     * @var int|null
     */
    public ?int $app_id;

    /**
     * Latency of the request in milliseconds
     *
     * @var int|null
     */
    public ?int $latency;

    /**
     * Moderation latency of the request in milliseconds
     *
     * @var int|null
     */
    public ?int $moderation_latency;

    /**
     * Upstream ID associated with the request
     *
     * @var string|null
     */
    public ?string $upstream_id;

    /**
     * Usage associated with the request
     *
     * @var float|null
     */
    public ?float $usage;
}
