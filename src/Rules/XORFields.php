<?php

namespace MoeMizrak\LaravelOpenrouter\Rules;

use Illuminate\Support\Arr;
use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;

/**
 * Validator class for XOR-gate first and second fields.
 * If firstField exists and secondField NOT exist, or vice versa, the output is TRUE -> validated.
 * If both firstField and secondField exist, or both are NOT exist, the output is FALSE -> validation failed.
 *
 * Class XORFields
 * @package MoeMizrak\LaravelOpenrouter\Rules
 */
class XORFields implements Validator
{
    /**
     * Constructor a new validation instance.
     *
     * @param string $firstField
     * @param string $secondField
     */
    public function __construct(protected string $firstField, protected string $secondField)
    {
    }

    /**
     * Validates the required fields.
     *
     * @param mixed $params
     * @return ValidationResult
     */
    public function validate(mixed $params): ValidationResult
    {
        $firstFieldExists = Arr::has($params, $this->firstField);
        $secondFieldExists = Arr::has($params, $this->secondField);

        // If XOR condition is met, return valid
        if ($firstFieldExists xor $secondFieldExists) {
            return ValidationResult::valid();
        }

        return ValidationResult::invalid("Fields " . $this->firstField . " and " . $this->secondField . " are XOR-gated, compelling the requirement of either one, but not both simultaneously.");
    }
}