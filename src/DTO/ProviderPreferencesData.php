<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Exceptions\OpenRouterValidationException;
use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;
use MoeMizrak\LaravelOpenrouter\Types\DataCollectionType;
use MoeMizrak\LaravelOpenrouter\Types\ProviderSortType;

/**
 * DTO for the provider preferences.
 * For more info: https://openrouter.ai/docs/guides/routing/provider-selection
 *
 * Class ProviderPreferencesData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class ProviderPreferencesData extends DataTransferObject
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * Whether to allow backup providers to serve requests.
         * true: (default) when the primary provider is unavailable, use the next best provider.
         * false: use only the primary provider, and return the upstream error if it's unavailable.
         *
         * @var bool|null
         */
        public ?bool $allow_fallbacks = null,

        /**
         * Whether to filter providers to only those that support the parameters you've provided.
         * If this setting is omitted or set to false, then providers will receive only the parameters they support, and ignore the rest.
         *
         * @var bool|null
         */
        public ?bool $require_parameters = null,

        /**
         * Data collection setting. If no available model provider meets the requirement, your request will return an error.
         * allow: (default) allow providers which store user data non-transiently and may train on it.
         * deny: use only providers which do not collect user data.
         *
         * @var string|null
         */
        #[AllowedValues([DataCollectionType::ALLOW, DataCollectionType::DENY])]
        public ?string $data_collection = null,

        /**
         * An ordered list of provider names.
         * The router will attempt to use the first provider in the subset of this list that supports your requested model,
         * and fall back to the next if it is unavailable. If no providers are available, the request will fail with an error message.
         *
         * @var array|null
         */
        public ?array $order = null,

        /**
         * Restrict routing to only Zero Data Retention (ZDR) endpoints.
         *
         * @var bool|null
         */
        public ?bool $zdr = null,

        /**
         * Restrict routing to only models that allow text distillation.
         *
         * @var bool|null
         */
        public ?bool $enforce_distillable_text = null,

        /**
         * List of provider slugs to allow for this request.
         *
         * @var array|null
         */
        public ?array $only = null,

        /**
         * List of provider slugs to skip for this request.
         *
         * @var array|null
         */
        public ?array $ignore = null,

        /**
         * Filter providers by quantization levels.
         *
         * @var array|null
         */
        public ?array $quantizations = null,

        /**
         * Sort providers by attribute. Can be a string ("price", "throughput", "latency") or a ProviderSortData object.
         *
         * @var string|ProviderSortData|null
         */
        public string|ProviderSortData|null $sort = null,

        /**
         * Preferred minimum throughput (tokens/sec). Can be a number or a PercentileData object with percentile cutoffs.
         *
         * @var float|PercentileData|null
         */
        public float|PercentileData|null $preferred_min_throughput = null,

        /**
         * Preferred maximum latency (seconds). Can be a number or a PercentileData object with percentile cutoffs.
         *
         * @var float|PercentileData|null
         */
        public float|PercentileData|null $preferred_max_latency = null,

        /**
         * Maximum acceptable pricing per request.
         *
         * @var MaxPriceData|null
         */
        public ?MaxPriceData $max_price = null,
    ) {
        // Validate sort string value manually since AllowedValues attribute cannot handle union types (string|ProviderSortData).
        if (is_string($this->sort) && ! in_array($this->sort, [ProviderSortType::PRICE, ProviderSortType::THROUGHPUT, ProviderSortType::LATENCY])) {
            throw new OpenRouterValidationException(
                "Value is NOT allowed: " . $this->sort . " - Allowed values: " . implode(', ', [ProviderSortType::PRICE, ProviderSortType::THROUGHPUT, ProviderSortType::LATENCY])
            );
        }

        parent::__construct(...func_get_args());
    }

    /**
     * @return array
     */
    public function convertToArray(): array
    {
        return array_filter(
            [
                'allow_fallbacks'          => $this->allow_fallbacks,
                'require_parameters'       => $this->require_parameters,
                'data_collection'          => $this->data_collection,
                'order'                    => $this->order,
                'zdr'                      => $this->zdr,
                'enforce_distillable_text' => $this->enforce_distillable_text,
                'only'                     => $this->only,
                'ignore'                   => $this->ignore,
                'quantizations'            => $this->quantizations,
                'sort'                     => $this->sort instanceof ProviderSortData
                    ? ($this->sort->convertToArray() ?: null)
                    : $this->sort,
                'preferred_min_throughput'  => $this->preferred_min_throughput instanceof PercentileData
                    ? ($this->preferred_min_throughput->convertToArray() ?: null)
                    : $this->preferred_min_throughput,
                'preferred_max_latency'    => $this->preferred_max_latency instanceof PercentileData
                    ? ($this->preferred_max_latency->convertToArray() ?: null)
                    : $this->preferred_max_latency,
                'max_price'                => $this->max_price?->convertToArray(),
            ],
            fn($value) => $value !== null
        );
    }
}
