<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

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
     * @param int|null $requests
     * @param string|null $interval
     */
    public function __construct(
        /**
         * Number of requests allowed.
         *
         * @var int|null
         */
        public ?int $requests = null,

        /**
         * In this interval, e.g. "10s"
         *
         * @var string|null
         */
        public ?string $interval = null
    ) {
        parent::__construct(...func_get_args());
    }
}