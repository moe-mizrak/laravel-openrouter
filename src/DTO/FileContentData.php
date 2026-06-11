<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;

/**
 * DTO for file/document content in messages.
 */
final class FileContentData extends DataTransferObject
{
    /**
     * The allowed type value for file content.
     */
    public const ALLOWED_TYPE = 'file';

    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * Type of the content. (i.e. file)
         */
        #[AllowedValues([self::ALLOWED_TYPE])]
        public string $type = self::ALLOWED_TYPE,

        /**
         * File data object containing URL or base64 data.
         */
        public FileUrlData $file,
    ) {
        parent::__construct(...func_get_args());
    }

    public function convertToArray(): array
    {
        return array_filter(
            [
                'type' => $this->type,
                'file' => $this->file?->convertToArray(),
            ],
            fn($value) => $value !== null
        );
    }
}
