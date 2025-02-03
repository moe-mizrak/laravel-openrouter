<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * StreamingChoiceData is the DTO choice type for streaming responses
 *
 * Class StreamingChoiceData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class StreamingChoiceData extends ChoiceData
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * Any changed fields on a message during streaming.
         *
         * @var DeltaData
         */
        public DeltaData $delta
    ) {
        parent::__construct();
    }
}