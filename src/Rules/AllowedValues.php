<?php

namespace MoeMizrak\LaravelOpenrouter\Rules;

use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;

/**
 * Validator class for checking whether the value is in allowed value list.
 */
#[\Attribute]
class AllowedValues implements Validator
{
    /**
     * Constructor a new validation instance.
     *
     * @param array $acceptableValues
     */
    public function __construct(protected array $acceptableValues = [])
    {
    }

    /**
     * Validates the allowed values.
     *
     * @param mixed $value
     * @return ValidationResult
     */
    public function validate(mixed $value): ValidationResult
    {
        if (in_array($value, $this->acceptableValues) || is_null($value) || is_array($value)) {
            return ValidationResult::valid();
        }

        return ValidationResult::invalid("Value is NOT allowed: ". $value);
    }
}