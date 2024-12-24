<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\LaravelData\Data as DataTransferObject;

/**
 * DTO that represents a message delta i.e. any changed fields on a message during streaming.
 *
 * Class DeltaData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class DeltaData extends DataTransferObject
{
    /**
     * @param string|null $content
     * @param string|null $role
     * @param ToolCallData[]|null $toolCalls
     */
    public function __construct(
        /**
         * The content of the message.
         *
         * @var string|null
         */
        public ?string $content = null,

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
        public ?array $toolCalls = null
    ) {}
}