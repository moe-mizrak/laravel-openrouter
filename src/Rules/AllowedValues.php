<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Rules;

use MoeMizrak\LaravelOpenrouter\Exceptions\OpenRouterValidationException;

/**
 * Validator class for checking whether the value is in allowed value list.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
final readonly class AllowedValues
{
    public function __construct(protected array $acceptableValues = []) {}

    /**
     * Validates the allowed values.
     *
     * @throws OpenRouterValidationException
     */
    public function handle(mixed $value): void
    {
        if (in_array($value, $this->acceptableValues) || is_null($value)) {
            return;
        }

        throw new OpenRouterValidationException(
            'Value is NOT allowed: ' . $value . ' - Allowed values: ' . implode(', ', $this->acceptableValues)
        );
    }
}
