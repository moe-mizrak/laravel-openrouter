<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * StreamingChoiceData is the DTO choice type for streaming responses
 */
final class StreamingChoiceData extends ChoiceData
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * Any changed fields on a message during streaming.
         */
        public DeltaData $delta
    ) {
        parent::__construct();
    }
}
