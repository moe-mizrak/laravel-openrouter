<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for maximum acceptable pricing per request.
 * For more info: https://openrouter.ai/docs/guides/routing/provider-selection
 */
final class MaxPriceData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * Maximum price per prompt token (in USD).
         */
        public ?float $prompt = null,

        /**
         * Maximum price per completion token (in USD).
         */
        public ?float $completion = null,

        /**
         * Maximum price per request (in USD).
         */
        public ?float $request = null,

        /**
         * Maximum price per image (in USD).
         */
        public ?float $image = null,
    ) {
        parent::__construct(...func_get_args());
    }

    public function convertToArray(): array
    {
        return array_filter(
            [
                'prompt' => $this->prompt,
                'completion' => $this->completion,
                'request' => $this->request,
                'image' => $this->image,
            ],
            fn($value) => $value !== null
        );
    }
}
