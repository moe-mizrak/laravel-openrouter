<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for file URL/data wrapper.
 * Supports both direct URLs and base64 data URIs for documents.
 *
 * Class FileUrlData
 */
final class FileUrlData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * File URL or base64 data URI.
         * Formats:
         * - URL: 'https://example.com/document.pdf'
         * - Base64: 'data:application/pdf;base64,JVBERi0xLjQK...'
         *
         * @var string
         */
        public string $file_data,

        /**
         * Optional filename for context.
         *
         * @var string|null
         */
        public ?string $filename = null,
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
                'file_data' => $this->file_data,
                'filename' => $this->filename,
            ],
            fn($value) => $value !== null
        );
    }
}
