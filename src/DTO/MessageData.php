<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO that represents a message i.e. any changed fields on a message.
 *
 * Class MessageData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class MessageData extends DataTransferObject
{
    /**
     * @param array|ImageContentPartData[]|TextContentData[]|string|null $content
     * @param string|null $role
     * @param ToolCallData[]|null $toolCalls
     * @param string|null $name
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
         * Calling tools e.g. function
         *
         * @var ToolCallData[]|null
         */
        public ?array $toolCalls = null,

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
}