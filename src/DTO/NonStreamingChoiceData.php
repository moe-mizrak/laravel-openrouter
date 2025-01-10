<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * NonStreamingChoiceData is the DTO choice type for non-streaming responses.
 *
 * Class NonStreamingChoiceData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class NonStreamingChoiceData extends ChoiceData
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * DTO of the message data.
         *
         * @var MessageData
         */
        public MessageData $message
    ) {
        parent::__construct();
    }
}