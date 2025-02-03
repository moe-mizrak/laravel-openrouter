<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for the image url which are url and optional detail.
 *
 * Class ImageUrlData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ImageUrlData extends DataTransferObject
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * URL or base64 encoded image data
         *
         * @var string
         */
        public string $url,

        /**
         * Optional, defaults to 'auto'
         *
         * @var string|null
         */
        public ?string $detail = null
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
                'url'    => $this->url,
                'detail' => $this->detail,
            ],
            fn($value) => $value !== null
        );
    }
}