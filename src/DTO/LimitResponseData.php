<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\LaravelData\Data as DataTransferObject;

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
     * @param string|null $label
     * @param float|null $usage
     * @param float|null $limit_remaining
     * @param int|null $limit
     * @param bool|null $is_free_tier
     * @param RateLimitData|null $rate_limit
     */
    public function __construct(
        /**
         * Label of the limit e.g. sk-or-v1-f35...ebd
         *
         * @var string|null
         */
        public ?string $label = null,

        /**
         * Number of credits used.
         *
         * @var float|null
         */
        public ?float $usage = null,

        /**
         * @var float|null
         */
        public ?float $limit_remaining = null,

        /**
         * Credit limit for the key, or null if unlimited.
         *
         * @var int|null
         */
        public ?int $limit = null,

        /**
         * Whether the user has paid for credits before.
         *
         * @var bool|null
         */
        public ?bool $is_free_tier = null,

        /**
         * Rate limit DTO data.
         *
         * @var RateLimitData|null
         */
        public ?RateLimitData $rate_limit = null
    ) {}
}