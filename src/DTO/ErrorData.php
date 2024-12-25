<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for error messages.
 *
 * Class ErrorData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ErrorData extends DataTransferObject
{
    /**
     * @param int $code
     * @param string $message
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