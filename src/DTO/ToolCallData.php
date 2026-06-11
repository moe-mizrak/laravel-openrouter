<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * An array of tool calls the run step was involved in.
 * These can be associated with one of three types of tools: code_interpreter, file_search, or function.
 */
final class ToolCallData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * ID of the tool call.
         */
        public ?string $id = null,

        /**
         * Name of the tool. (i.e. function)
         */
        public ?string $type = null,

        public ?FunctionData $function = null
    ) {
        parent::__construct(...func_get_args());
    }

    public function convertToArray(): array
    {
        return array_filter(
            [
                'id' => $this->id,
                'type' => $this->type,
                'function' => $this->function?->convertToArray(),
            ],
            fn($value) => $value !== null
        );
    }
}
