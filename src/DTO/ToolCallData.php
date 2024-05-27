<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * An array of tool calls the run step was involved in.
 * These can be associated with one of three types of tools: code_interpreter, file_search, or function.
 *
 * Class ToolCallData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ToolCallData extends DataTransferObject
{
    /**
     * ID of the tool call.
     *
     * @var string|null
     */
    public ?string $id;

    /**
     * Name of the tool. (i.e. function)
     *
     * @var string|null
     */
    public ?string $type;

    /**
     * Function DTO object.
     *
     * @var FunctionData|null
     */
    public ?FunctionData $function;
}