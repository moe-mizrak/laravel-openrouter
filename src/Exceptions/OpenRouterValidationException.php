<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Exceptions;

use Exception;

/**
 * This exception is thrown when a validation rule fails.
 */
final class OpenRouterValidationException extends Exception
{
    /**
     * The constructor initializes the exception with a custom error message.
     * The default message is 'Validation failed', but a custom message can be provided.
     */
    public function __construct(string $message = 'Validation failed')
    {
        parent::__construct($message);
    }
}
