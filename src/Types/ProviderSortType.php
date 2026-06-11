<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Types;

/**
 * This class keeps provider sort types for sorting providers by attribute.
 * For more info: https://openrouter.ai/docs/guides/routing/provider-selection
 */
final readonly class ProviderSortType
{
    /**
     * Sort by price (ascending).
     */
    const PRICE = 'price';

    /**
     * Sort by throughput (descending).
     */
    const THROUGHPUT = 'throughput';

    /**
     * Sort by latency (ascending).
     */
    const LATENCY = 'latency';
}
