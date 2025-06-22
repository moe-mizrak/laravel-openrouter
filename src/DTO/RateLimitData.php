<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * RateLimitData is the response DTO for rate limit which consists of:
 *  - requests
 *  - interval
 *
 * Class RateLimitData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class RateLimitData extends DataTransferObject
{
    /**
     * @inheritDoc
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

    /**
     * @inheritDoc
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