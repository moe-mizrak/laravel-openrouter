<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\LaravelData\Data as DataTransferObject;

/**
 * ChoiceData is the DTO for the choices of the api call.
 *
 * Class ChoiceData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ChoiceData extends DataTransferObject
{
    /**
     * ChoiceData constructor.
     *
     * @param string|null $finish_reason
     * @param ErrorData|null $error
     */
    public function __construct(
        /**
         * Depends on the model. Ex: 'stop' | 'length' | 'content_filter' | 'tool_calls' | 'function_call' ...
         *
         * @var string|null
         */
        public ?string $finish_reason = null,

        /**
         * Error returned from the API request
         *
         * @var ErrorData|null
         */
        public ?ErrorData $error = null
    ) {}
}