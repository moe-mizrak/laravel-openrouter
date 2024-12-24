<?php

namespace MoeMizrak\LaravelOpenrouter\Rules;

use MoeMizrak\LaravelOpenrouter\DTO\ValidationResultData;

/**
 * Validator class for XOR-gate first and second fields.
 * If firstField exists and secondField NOT exist, or vice versa, the output is TRUE -> validated.
 * If both firstField and secondField exist, or both are NOT exist, the output is FALSE -> validation failed.
 *
 * Class XORFields
 * @package MoeMizrak\LaravelOpenrouter\Rules
 */
class XORFields
{
    /**
     * Constructor a new validation instance.
     *
     * @param mixed $firstField
     * @param mixed $secondField
     */
    public function __construct(protected mixed $firstField, protected mixed $secondField)
    {}

    /**
     * Validate XOR condition for two fields.
     *
     * @return ValidationResultData
     */
    public function validate(): ValidationResultData
    {
        $isValid = (empty($this->firstField) xor empty($this->secondField));

        /**
         * Set the fields that have xor relation.
         */
        $xorFields = [
            ['messages', 'prompt'], // messages and prompt fields are XOR gated
            ['model', 'models'], // model and models fields are XOR gated
        ];
        // e.g. "messages and prompt"
        foreach ($xorFields as $pair) {
            $result[] = implode(' and ', $pair);
        }
        // e.g. "messages and prompt, model and models"
        $stringXorFields = implode(', ', $result);

        $message = $isValid
            ? null
            : "Fields {$stringXorFields} are XOR-gated, meaning exactly one field from each pair must be provided, but not both.";

        return new ValidationResultData(
            isValid: $isValid,
            message: $message
        );
    }
}