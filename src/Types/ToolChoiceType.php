<?php

namespace MoeMizrak\LaravelOpenrouter\Types;

/**
 * This class keeps tool choice types.
 * Validation for the tool choice parameter is added in ChatData DTO class since it can only get:
 * none, auto or ToolCallData (in OpenAI it is set as 'required', it means the model must call one or more tools.)
 *
 * Class ToolChoiceType
 * @package MoeMizrak\LaravelOpenrouter\Types
 */
class ToolChoiceType
{
    /**
     * auto means the model can pick between generating a message or calling one or more tools.
     *
     * @var string
     */
    const AUTO = 'auto';

    /**
     * none means the model will not call any tool and instead generates a message
     *
     * @var string
     */
    const NONE = 'none';
}