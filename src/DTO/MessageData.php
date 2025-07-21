<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO that represents a message i.e. any changed fields on a message.
 *
 * Class MessageData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class MessageData extends DataTransferObject
{
    /**
     * @inheritDoc
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

    /**
     * @return array
     */
    public function convertToArray(): array
    {
        return array_filter(
            [
                'content'   => is_array($this->content)
                    ? array_map(function ($value) {
                        if ($value instanceof TextContentData) {
                            return $value->convertToArray();
                        } elseif ($value instanceof ImageContentPartData) {
                            return $value->convertToArray();
                        } else {
                            return $value;
                        }
                        }, $this->content)
                    : $this->content,
                'role'      => $this->role,
                'toolCalls' => ! is_null($this->toolCalls)
                    ? array_map(function ($value) {
                        return $value->convertToArray();
                        }, $this->toolCalls)
                    : null,
                'name'      => $this->name,
            ],
            fn($value) => $value !== null
        );
    }
}