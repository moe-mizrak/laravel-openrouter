<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * DTO for the image url which are url and optional detail.
 *
 * Class ImageUrlData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ImageUrlData extends DataTransferObject
{
    /**
     * URL or base64 encoded image data
     *
     * @var string
     */
    public string $url;

    /**
     * Optional, defaults to 'auto'
     *
     * @var string|null
     */
    public ?string $detail;
}