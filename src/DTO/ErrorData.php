<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for error messages.
 *
 * Class ErrorData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class ErrorData extends DataTransferObject
{
    /**
     * @inheritDoc
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
        public string $message
    ) {
        parent::__construct(...func_get_args());
    }
}