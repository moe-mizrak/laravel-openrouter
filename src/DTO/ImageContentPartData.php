<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\LaravelData\Data as DataTransferObject;
use Spatie\LaravelData\Attributes\Validation\In;

/**
 * DTO for the image contents.
 *
 * Class ImageContentPartData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ImageContentPartData extends DataTransferObject
{
    /**
     * The allowed type for image content.
     */
    public const ALLOWED_TYPE = 'image_url';

    /**
     * @param ImageUrlData $image_url
     */
    public function __construct(
        /**
         * Type of the content. (i.e. image_url)
         *
         * @var string
         */
        #[In([self::ALLOWED_TYPE])]
        public string $type = self::ALLOWED_TYPE,

        /**
         * DTO of image url.
         *
         * @var ImageUrlData
         */
        public ImageUrlData $image_url
    ) {}
}