<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * ChoiceData is the DTO for the choices of the api call.
 *
 * Class ChoiceData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ChoiceData extends DataTransferObject
{
    /**
     * Depends on the model. Ex: 'stop' | 'length' | 'content_filter' | 'tool_calls' | 'function_call' ...
     *
     * @var string|null
     */
    public ?string $finish_reason;

    /**
     * Error returned from the api request
     *
     * @var ErrorData|null
     */
    public ?ErrorData $error;
}