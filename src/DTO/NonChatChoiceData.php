<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * NonChatChoiceData is the DTO choice type for non-chat responses
 *
 * Class NonChatChoiceData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class NonChatChoiceData extends ChoiceData
{
    /**
     * @param string $text
     */
    public function __construct(
        /**
         * The text of the choice
         */
        public string $text
    ) {
        parent::__construct();
    }
}