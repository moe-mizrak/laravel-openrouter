<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;
use MoeMizrak\LaravelOpenrouter\Types\ProviderSortType;

/**
 * DTO for the provider sort configuration object.
 * For more info: https://openrouter.ai/docs/guides/routing/provider-selection
 *
 * Class ProviderSortData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class ProviderSortData extends DataTransferObject
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * The attribute to sort by.
         *
         * @var string|null
         */
        #[AllowedValues([ProviderSortType::PRICE, ProviderSortType::THROUGHPUT, ProviderSortType::LATENCY])]
        public ?string $by = null,

        /**
         * Whether to partition results into available and unavailable groups.
         *
         * @var bool|null
         */
        public ?bool $partition = null,
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
                'by'        => $this->by,
                'partition' => $this->partition,
            ],
            fn($value) => $value !== null
        );
    }
}
