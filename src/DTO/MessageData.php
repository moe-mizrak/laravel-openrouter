<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO that represents a message i.e. any changed fields on a message.
 *
 * Class MessageData
 */
final class MessageData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * The content of the message.
         *
         * @var string|TextContentData[]|ImageContentPartData[]|array|null
         */
        public string|array|null $content = null,

        /**
         * The entity that produced the message.
         * Possible values are user, assistant, system, function, tool
         *
         * @var string|null
         */
        public ?string $role = null,

        /**
         * @var string|null
         */
        public ?string $refusal = null,

        /**
         * Reasoning for the message.
         *
         * @var string|null
         */
        public ?string $reasoning = null,

        /**
         * Calling tools e.g. function
         *
         * @var ToolCallData[]|null
         */
        public ?array $tool_calls = null,

        /**
         * That is the identifier that connects the tool result back to the tool call the LLM requested.
         * Used to specify which tool to call when multiple tools are provided.
         *
         * @var string|null
         */
        public ?string $tool_call_id = null,

        /**
         * An optional name for the participant. Provides the model information to differentiate between participants of the same role.
         * e.g. name: "Moe"
         *
         * @var string|null
         */
        public ?string $name = null,
    ) {
        parent::__construct(...func_get_args());
    }

    /**
     * @return array
     */
    public function convertToArray(): array
    {
        return array_filter(
            [
                'content' => is_array($this->content)
                    ? array_map(function ($value) {
                        if (
                            $value instanceof TextContentData
                            || $value instanceof ImageContentPartData
                            || $value instanceof AudioContentData
                            || $value instanceof FileContentData
                        ) {
                            return $value->convertToArray();
                        } else {
                            return $value;
                        }
                    }, $this->content)
                    : $this->content,
                'role' => $this->role,
                'tool_calls' => ! is_null($this->tool_calls)
                    ? array_map(function ($value) {
                        return $value->convertToArray();
                    }, $this->tool_calls)
                    : null,
                'tool_call_id' => $this->tool_call_id,
                'name' => $this->name,
            ],
            fn($value) => $value !== null
        );
    }
}
