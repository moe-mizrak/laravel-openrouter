<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * NonStreamingChoiceData is the DTO choice type for non-streaming responses.
 *
 * Class NonStreamingChoiceData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class NonStreamingChoiceData extends ChoiceData
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