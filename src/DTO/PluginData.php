<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * PluginData is the DTO for the plugins of the api call.
 *
 * Check https://openrouter.ai/docs/guides/features/web-search
 * Also check https://openrouter.ai/docs/guides/overview/multimodal/pdfs
 *
 * Class PluginData
 */
final class PluginData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * Unique identifier for the plugin e.g. 'web' for web search, 'file-parser' for file inputs
         *
         * @var string
         */
        public string $id,

        /**
         * Engine or method used by the plugin.
         * e.g. "native", "exa", or undefined
         * Check https://openrouter.ai/docs/guides/features/web-search
         *
         * @var string|null
         */
        public ?string $engine = null,

        /**
         * Maximum number of results to return.
         * Defaults to 5 if not specified.
         *
         * @var int|null
         */
        public ?int $max_results = null,

        /**
         * Search prompt to guide the plugin's operation.
         * By default, the web plugin uses the following search prompt, using the current date:
         * "A web search was conducted on `date`. Incorporate the following web search results into your response.
         * IMPORTANT: Cite them using markdown links named using the domain of the source.
         * Example: [nytimes.com](https://nytimes.com/some-page)."
         *
         * @var string|null
         */
        public ?string $search_prompt = null,

        /**
         * PDF file input plugin data
         *
         * e.g. ['engine' => 'pdf-text']
         * OpenRouter provides several PDF processing engines: mistral-ocr, pdf-text, native
         * Check https://openrouter.ai/docs/guides/overview/multimodal/pdfs
         *
         * @var array|null
         */
        public ?array $pdf = null,
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
                'id' => $this->id,
                'engine' => $this->engine,
                'max_results' => $this->max_results,
                'search_prompt' => $this->search_prompt,
                'pdf' => $this->pdf,
            ],
            fn($value) => $value !== null
        );
    }
}
