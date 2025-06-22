<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

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
final class LimitResponseData extends DataTransferObject
{
    /**
     * @inheritDoc
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
                'label'           => $this->label,
                'usage'           => $this->usage,
                'limit_remaining' => $this->limit_remaining,
                'limit'           => $this->limit,
                'is_free_tier'    => $this->is_free_tier,
                'rate_limit'      => $this->rate_limit?->toArray(),
            ],
            fn($value) => $value !== null
        );
    }
}