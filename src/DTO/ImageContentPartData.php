<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;
use Spatie\DataTransferObject\DataTransferObject;

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
     * Type of the content. (i.e. image_url)
     *
     * @var string
     */
    #[AllowedValues([self::ALLOWED_TYPE])]
    public string $type = self::ALLOWED_TYPE;

    /**
     * DTO of image url.
     *
     * @var ImageUrlData
     */
    public ImageUrlData $image_url;
}