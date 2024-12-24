<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\LaravelData\Data as DataTransferObject;
use Spatie\LaravelData\Attributes\Validation\In;

/**
 * DTO for the contents.
 *
 * Class TextContentData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class TextContentData extends DataTransferObject
{
    /**
     * The allowed type for content.
     */
    public const ALLOWED_TYPE = 'text';

    /**
     * @param string $type
     * @param string $text
     */
    public function __construct(
        /**
         * Type of the content. (i.e. text)
         *
         * @var string
         */
        #[In([self::ALLOWED_TYPE])]
        public string $type = self::ALLOWED_TYPE,

        /**
         * Text of the content.
         *
         * @var string
         */
        public string $text,
    ) {}
}