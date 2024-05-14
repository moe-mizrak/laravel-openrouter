<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * DTO that represents a message delta i.e. any changed fields on a message during streaming.
 *
 * Class DeltaData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class DeltaData extends DataTransferObject
{
    /**
     * The content of the message.
     *
     * @var string|null
     */
    public ?string $content;

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
}