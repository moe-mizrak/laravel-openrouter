<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Types;

/**
 * Search context size type for web search options.
 *
 * For more info: https://openrouter.ai/docs/guides/features/plugins/web-search#specifying-search-context-size
 */
final readonly class SearchContextSizeType
{
    /**
     * Minimal search context, suitable for basic queries.
     */
    public const LOW = 'low';

    /**
     * Moderate search context, good for general queries.
     */
    public const MEDIUM = 'medium';

    /**
     * Extensive search context, ideal for detailed research.
     */
    public const HIGH = 'high';
}
