<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Types;

/**
 * This class keeps provider sort types for sorting providers by attribute.
 * For more info: https://openrouter.ai/docs/guides/routing/provider-selection
 *
 * Class ProviderSortType
 * @package MoeMizrak\LaravelOpenrouter\Types
 */
final readonly class ProviderSortType
{
    /**
     * Sort by price (ascending).
     *
     * @var string
     */
    const PRICE = 'price';

    /**
     * Sort by throughput (descending).
     *
     * @var string
     */
    const THROUGHPUT = 'throughput';

    /**
     * Sort by latency (ascending).
     *
     * @var string
     */
    const LATENCY = 'latency';
}
