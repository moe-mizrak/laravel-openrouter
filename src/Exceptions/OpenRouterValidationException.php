<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Exceptions;

use Exception;

/**
 * This exception is thrown when a validation rule fails.
 *
 * Class OpenRouterValidationException
 * @package MoeMizrak\LaravelOpenrouter\Exceptions
 */
final class OpenRouterValidationException extends Exception
{
    /**
     * OpenRouterValidationException constructor.
     * The constructor initializes the exception with a custom error message.
     *  The default message is 'Validation failed', but a custom message can be provided.
     *
     * @param string $message Custom error message for the exception
     */
    public function __construct(string $message = 'Validation failed')
    {
        parent::__construct($message);
    }
}