<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for error messages.
 *
 * Class ErrorData
 */
final class ErrorData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * Error code e.g. 400, 408 ...
         *
         * @var int
         */
        public int $code,

        /**
         * Error message.
         *
         * @var string
         */
        public string $message,

        /**
         * Additional metadata about the error.
         *
         * @var array|null
         */
        public ?array $metadata = null
    ) {
        parent::__construct(...func_get_args());
    }
}
