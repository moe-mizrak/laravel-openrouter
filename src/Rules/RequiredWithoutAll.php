<?php

namespace MoeMizrak\LaravelOpenrouter\Rules;

use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;

/**
 * Validator class for checking attribute required when none of fields are present .
 */
#[\Attribute]
class RequiredWithoutAll implements Validator
{
    /**
     * Constructor a new validation instance.
     *
     * @param array $requiredFields
     */
    public function __construct(protected array $requiredFields = [])
    {
    }

    /**
     * Validates the required fields.
     *
     * @param mixed $value
     * @return ValidationResult
     */
    public function validate(mixed $value): ValidationResult
    {
        $presentFields = 0;

        foreach ($this->requiredFields as $field) {
            if (!empty($value->$field)) {
                $presentFields++;
            }
        }

        // If all specified fields are present, the current field is not required
        if ($presentFields === count($this->requiredFields)) {
            return ValidationResult::valid();
        }

        // If none of the specified fields are present, check if the current field has a value
        if (!empty($value)) {
            return ValidationResult::valid();
        }

        return ValidationResult::invalid("Field is required when none of the following fields are present: " . implode(', ', $this->requiredFields));
    }
}