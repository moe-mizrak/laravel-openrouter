<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;
use MoeMizrak\LaravelOpenrouter\Types\EffortType;

/**
 * ReasoningData is the DTO for the reasoning parameters of the API call.
 * For more info: https://openrouter.ai/docs/use-cases/reasoning-tokens
 *
 * Class ReasoningData
 *
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class ReasoningData extends DataTransferObject
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * OpenAI-style reasoning effort setting
         *
         * @var string|null
         */
        #[AllowedValues([EffortType::HIGH, EffortType::MEDIUM, EffortType::LOW])]
        public ?string $effort = null,

        /**
         * Non-OpenAI-style reasoning effort setting.
         * Note: Cannot be used simultaneously with effort.
         *
         * @var int|null
         */
        public ?int $max_tokens = null,

        /**
         * Whether to exclude reasoning from the response
         *
         * @var bool|null
         */
        public ?bool $exclude = false,

        /**
         * Enable reasoning with the default parameters.
         * Default: inferred from `effort` or `max_tokens`
         *
         * @var bool|null
         */
        public ?bool $enabled = null,
    ) {
        parent::__construct(...func_get_args());
    }

    /**
     * @return array
     */
    public function convertToArray(): array
    {
        return array_filter(
            [
                'effort'     => $this->effort,
                'max_tokens' => $this->max_tokens,
                'exclude'    => $this->exclude,
                'enabled'    => $this->enabled,
            ],
            fn($value) => $value !== null
        );
    }
}