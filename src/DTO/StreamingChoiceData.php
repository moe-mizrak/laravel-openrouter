<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * StreamingChoiceData is the DTO choice type for streaming responses
 *
 * Class StreamingChoiceData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class StreamingChoiceData extends ChoiceData
{
    /**
     * StreamingChoiceData constructor.
     *
     * @param DeltaData $delta
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