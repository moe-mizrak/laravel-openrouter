<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;

/**
 * DTO for the image contents.
 *
 * Class ImageContentPartData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class ImageContentPartData extends DataTransferObject
{
    /**
     * The allowed type for image content.
     */
    public const ALLOWED_TYPE = 'image_url';

    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * Type of the content. (i.e. image_url)
         *
         * @var string
         */
        #[AllowedValues([self::ALLOWED_TYPE])]
        public string $type = self::ALLOWED_TYPE,

        /**
         * DTO of image url.
         *
         * @var ImageUrlData
         */
        public ImageUrlData $image_url
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
                'type'      => $this->type,
                'image_url' => $this->image_url?->convertToArray(),
            ],
            fn($value) => $value !== null
        );
    }
}