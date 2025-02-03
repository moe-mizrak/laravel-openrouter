<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * NonChatChoiceData is the DTO choice type for non-chat responses
 *
 * Class NonChatChoiceData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class NonChatChoiceData extends ChoiceData
{
    /**
     * @inheritDoc
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