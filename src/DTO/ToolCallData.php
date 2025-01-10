<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

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
     * @inheritDoc
     */
    public function __construct(
        /**
         * ID of the tool call.
         *
         * @var string|null
         */
        public ?string $id = null,

        /**
         * Name of the tool. (i.e. function)
         *
         * @var string|null
         */
        public ?string $type = null,

        /**
         * Function DTO object.
         *
         * @var FunctionData|null
         */
        public ?FunctionData $function = null
    ) {
        parent::__construct(...func_get_args());
    }
}