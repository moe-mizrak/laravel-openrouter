<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for the image url which are url and optional detail.
 */
final class ImageUrlData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * URL or base64 encoded image data
         */
        public string $url,

        /**
         * Optional, defaults to 'auto'
         */
        public ?string $detail = null
    ) {
        parent::__construct(...func_get_args());
    }

    public function convertToArray(): array
    {
        return array_filter(
            [
                'url' => $this->url,
                'detail' => $this->detail,
            ],
            fn($value) => $value !== null
        );
    }
}
