<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for the validation result.
 * Contains whether the validation is successful and an optional message for failure.
 *
 * Class ValidationResultData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ValidationResultData extends DataTransferObject
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * Indicates if the validation passed.
         *
         * @var bool
         */
        public bool $isValid,

        /**
         * Message in case of validation failure.
         *
         * @var string|null
         */
        public ?string $message = null
    ) {
        parent::__construct(...func_get_args());
    }
}