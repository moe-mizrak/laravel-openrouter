<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * LimitResponseData is the response DTO for rate limit or credits left which consists of:
 *  - label
 *  - limit
 *  - usage
 *  - limit_remaining
 *  - is_free_tier
 *  - rate_limit (DTO object)
 *
 * Class LimitResponseData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class LimitResponseData extends DataTransferObject
{
    /**
     * Label of the limit e.g. sk-or-v1-f35...ebd
     *
     * @var string|null
     */
    public ?string $label;

    /**
     * Number of credits used.
     *
     * @var float|null
     */
    public ?float $usage;

    /**
     * @var float|null
     */
    public ?float $limit_remaining;

    /**
     * Credit limit for the key, or null if unlimited.
     *
     * @var int|null
     */
    public ?int $limit;

    /**
     * Whether the user has paid for credits before.
     *
     * @var bool|null
     */
    public ?bool $is_free_tier;

    /**
     * Rate limit DTO data.
     *
     * @var RateLimitData|null
     */
    public ?RateLimitData $rate_limit;
}