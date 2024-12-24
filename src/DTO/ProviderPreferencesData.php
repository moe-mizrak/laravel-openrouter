<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Types\DataCollectionType;
use Spatie\LaravelData\Data as DataTransferObject;
use Spatie\LaravelData\Attributes\Validation\In;

/**
 * DTO for the provider preferences.
 * for more info: https://openrouter.ai/docs#provider-routing
 *
 * Class ProviderPreferencesData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ProviderPreferencesData extends DataTransferObject
{
    /**
     * @param bool|null $allow_fallbacks
     * @param bool|null $require_parameters
     * @param string|null $data_collection
     * @param array|null $order
     */
    public function __construct(
        /**
         * Whether to allow backup providers to serve requests.
         * true: (default) when the primary provider is unavailable, use the next best provider.
         * false: use only the primary provider, and return the upstream error if it's unavailable.
         *
         * @var bool|null
         */
        public ?bool $allow_fallbacks = null,

        /**
         * Whether to filter providers to only those that support the parameters you've provided.
         * If this setting is omitted or set to false, then providers will receive only the parameters they support, and ignore the rest.
         *
         * @var bool|null
         */
        public ?bool $require_parameters = null,

        /**
         * Data collection setting. If no available model provider meets the requirement, your request will return an error.
         * allow: (default) allow providers which store user data non-transiently and may train on it.
         * deny: use only providers which do not collect user data.
         *
         * @var string|null
         */
        #[In([DataCollectionType::ALLOW, DataCollectionType::DENY])]
        public ?string $data_collection = null,

        /**
         * An ordered list of provider names.
         * The router will attempt to use the first provider in the subset of this list that supports your requested model,
         * and fall back to the next if it is unavailable. If no providers are available, the request will fail with an error message.
         *
         * enum: ["OpenAI", "Anthropic", "HuggingFace", "Google", "Mancer", "Mancer 2", "Together", "DeepInfra", "Azure", "Modal", "AnyScale", "Replicate", "Perplexity", "Recursal", "Fireworks", "Mistral", "Groq", "Cohere", "Lepton", "OctoAI", "Novita", "Lynn", "Lynn 2", "DeepSeek"]
         *
         * @var array|null
         */
        public ?array $order = null
    ) {}
}