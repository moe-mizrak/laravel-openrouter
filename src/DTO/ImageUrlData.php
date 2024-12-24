<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\LaravelData\Data as DataTransferObject;

/**
 * DTO for the image url which are url and optional detail.
 *
 * Class ImageUrlData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ImageUrlData extends DataTransferObject
{
    /**
     * @param string $url
     * @param string|null $detail
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
    ) {}
}