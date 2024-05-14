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
     * @var string
     */
    public string $text;
}