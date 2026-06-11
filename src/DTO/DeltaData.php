<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO that represents a message delta i.e. any changed fields on a message during streaming.
 */
final class DeltaData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * The content of the message.
         */
        public ?string $content = null,

        /**
         * The entity that produced the message.
         * Possible values are user, assistant, system, function, tool
         */
        public ?string $role = null,

        public ?string $refusal = null,
        public ?string $reasoning = null,

        /**
         * Calling tools e.g. function
         *
         * @var ToolCallData[]|null
         */
        public ?array $toolCalls = null
    ) {
        parent::__construct(...func_get_args());
    }
}
