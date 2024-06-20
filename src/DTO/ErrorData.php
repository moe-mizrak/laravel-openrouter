<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * DTO for error messages.
 *
 * Class ErrorData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ErrorData extends DataTransferObject
{
    /**
     * Error code e.g. 400, 408 ...
     *
     * @var int
     */
    public int $code;

    /**
     * Error message.
     *
     * @var string
     */
    public string $message;
}