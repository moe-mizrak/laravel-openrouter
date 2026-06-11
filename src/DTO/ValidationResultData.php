<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for the validation result.
 * Contains whether the validation is successful and an optional message for failure.
 */
final class ValidationResultData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * Indicates if the validation passed.
         */
        public bool $isValid,

        /**
         * Message in case of validation failure.
         */
        public ?string $message = null
    ) {
        parent::__construct(...func_get_args());
    }
}
