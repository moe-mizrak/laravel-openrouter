<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * NonStreamingChoiceData is the DTO choice type for non-streaming responses.
 */
final class NonStreamingChoiceData extends ChoiceData
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * DTO of the message data.
         */
        public MessageData $message
    ) {
        parent::__construct();
    }
}
