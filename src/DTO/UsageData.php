<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * UsageData is the DTO for the usage info of the api call.
 *
 * Class UsageData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class UsageData extends DataTransferObject
{
    /**
     * Equivalent to "native_tokens_completion" in the /generation API
     *
     * @var int|null
     */
    public ?int $prompt_tokens;

    /**
     * Equivalent to "native_tokens_prompt"
     *
     * @var int|null
     */
    public ?int $completion_tokens;

    /**
     * Sum of the above two fields ($prompt_tokens and $completion_tokens)
     *
     * @var int|null
     */
    public ?int $total_tokens;
}