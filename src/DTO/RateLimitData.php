<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * RateLimitData is the response DTO for rate limit which consists of:
 *  - requests
 *  - interval
 *
 * Class RateLimitData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class RateLimitData extends DataTransferObject
{
    /**
     * Number of requests allowed.
     *
     * @var int|null
     */
    public ?int $requests;

    /**
     * In this interval, e.g. "10s"
     *
     * @var string|null
     */
    public ?string $interval;
}