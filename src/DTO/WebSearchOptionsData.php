<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;
use MoeMizrak\LaravelOpenrouter\Types\SearchContextSizeType;

/**
 * DTO for web search options configuration.
 * For more info: https://openrouter.ai/docs/guides/features/web-search
 *
 * Class WebSearchOptionsData
 */
final class WebSearchOptionsData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * Search context size determines how much search data is retrieved and processed.
         * Options: 'low', 'medium', 'high'
         * - low: Minimal search context, suitable for basic queries
         * - medium: Moderate search context, good for general queries
         * - high: Extensive search context, ideal for detailed research
         *
         * @var string|null
         */
        #[AllowedValues([SearchContextSizeType::LOW, SearchContextSizeType::MEDIUM, SearchContextSizeType::HIGH])]
        public ?string $search_context_size = null,
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
                'search_context_size' => $this->search_context_size,
            ],
            fn($value) => $value !== null
        );
    }
}
