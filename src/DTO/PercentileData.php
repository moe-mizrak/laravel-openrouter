<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for percentile cutoff values used in preferred_min_throughput and preferred_max_latency.
 * For more info: https://openrouter.ai/docs/guides/routing/provider-selection
 *
 * Class PercentileData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class PercentileData extends DataTransferObject
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * 50th percentile cutoff value.
         *
         * @var float|null
         */
        public ?float $p50 = null,

        /**
         * 75th percentile cutoff value.
         *
         * @var float|null
         */
        public ?float $p75 = null,

        /**
         * 90th percentile cutoff value.
         *
         * @var float|null
         */
        public ?float $p90 = null,

        /**
         * 99th percentile cutoff value.
         *
         * @var float|null
         */
        public ?float $p99 = null,
    ) {
        parent::__construct(...func_get_args());
    }

    /**
     * @return array
     */
    public function convertToArray(): array
    {
        return array_filter(
            [
                'p50' => $this->p50,
                'p75' => $this->p75,
                'p90' => $this->p90,
                'p99' => $this->p99,
            ],
            fn($value) => $value !== null
        );
    }
}
