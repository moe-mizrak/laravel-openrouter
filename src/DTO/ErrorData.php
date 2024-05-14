<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for error messages.
 *
 * Class ErrorData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ErrorData
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