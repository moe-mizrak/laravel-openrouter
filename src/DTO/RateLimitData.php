<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * RateLimitData is the response DTO for rate limit which consists of:
 *  - requests
 *  - interval
 */
final class RateLimitData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * Number of requests allowed.
         */
        public ?int $requests = null,

        /**
         * In this interval, e.g. "10s"
         */
        public ?string $interval = null
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
                'requests' => $this->requests,
                'interval' => $this->interval,
            ],
            fn($value) => $value !== null
        );
    }
}
