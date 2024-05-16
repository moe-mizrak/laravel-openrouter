<?php

namespace MoeMizrak\LaravelOpenrouter\Exceptions;

use Exception;

/**
 * This exception is thrown when a validation rule for XOR condition fails.
 * Specifically, it is used when either one of two fields should be present,
 * but not both or neither.
 *
 * Class XorValidationException
 * @package MoeMizrak\LaravelOpenrouter\Exceptions
 */
class XorValidationException extends Exception
{
    /**
     * XorValidationException constructor.
     * The constructor initializes the exception with a custom error message.
     *  The default message is 'XOR validation failed', but a custom message can be provided.
     *
     * @param string $message Custom error message for the exception
     */
    public function __construct(string $message = 'XOR validation failed')
    {
        parent::__construct($message);
    }
}