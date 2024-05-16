<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * DTO that represents a message i.e. any changed fields on a message.
 *
 * Class MessageData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class MessageData extends DataTransferObject
{
    /**
     * The content of the message.
     *
     * @var string|TextContentData[]|ImageContentPartData[]|array|null
     */
    public string|array|null $content;

    /**
     * The entity that produced the message.
     * Possible values are user, assistant, system, function, tool
     *
     * @var string|null
     */
    public ?string $role;

    /**
     * Calling tools e.g. function
     *
     * @var ToolCallData[]|null
     */
    public ?array $toolCalls;

    /**
     * An optional name for the participant. Provides the model information to differentiate between participants of the same role.
     * e.g. name: "Moe"
     *
     * @var string|null
     */
    public ?string $name;
}