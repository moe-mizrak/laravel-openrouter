<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Exceptions\OpenRouterValidationException;
use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;
use MoeMizrak\LaravelOpenrouter\Rules\XORFields;
use MoeMizrak\LaravelOpenrouter\Types\RouteType;
use MoeMizrak\LaravelOpenrouter\Types\ToolChoiceType;

/**
 * DTO for the chat completion request.
 *
 * Class ChatData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class ChatData extends DataTransferObject
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * Message array consists of DTO data.
         * xor-gated with prompt field
         *
         * @var MessageData[]|null
         */
        public ?array $messages = null,

        /**
         * Prompt string data.
         * xor-gated with messages field
         *
         * @var string|null
         */
        public ?string $prompt = null,

        /**
         * Model name. If "model" is unspecified, uses the user's default.
         * For more info: https://openrouter.ai/docs#models
         *
         * @var string|null
         */
        public ?string $model = null,

        /**
         * The format of the output, e.g. json, text, srt, verbose_json ...
         *
         * @var ResponseFormatData|null
         */
        public ?ResponseFormatData $response_format = null,

        /**
         * Include usage information in the response.
         * This feature provides detailed information about token counts, costs, and caching status directly in your API responses
         * (Default value is false, enabling usage accounting will add a few hundred milliseconds to the last response as the API calculates token counts and costs)
         * See: https://openrouter.ai/docs/use-cases/usage-accounting
         *
         * @var bool
         */
        public bool $usage = false,

        /**
         * Stop generation immediately if the model encounters any token specified in the stop array|string.
         *
         * @var array|string|null
         */
        public array|string|null $stop = null,

        /**
         * Enable streaming.
         *
         * @var bool|null
         */
        public ?bool $stream = null,

        /**
         * See LLM Parameters (https://openrouter.ai/docs#parameters) for following:
         */
        public ?int $max_tokens = 1024, // Range: [1, context_length) The maximum number of tokens that can be generated in the completion. Default 1024.
        public ?float $temperature = null, // Range: [0, 2] Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic.
        public ?float $top_p = null, // Range: (0, 1] An alternative to sampling with temperature, called nucleus sampling, where the model considers the results of the tokens with top_p probability mass.
        public ?float $top_k = null, // Range: [1, Infinity) Not available for OpenAI models
        public ?float $frequency_penalty = null, // Range: [-2, 2] Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model's likelihood to repeat the same line verbatim.
        public ?float $presence_penalty = null, // Range: [-2, 2] Positive values penalize new tokens based on whether they appear in the text so far, increasing the model's likelihood to talk about new topics.
        public ?float $repetition_penalty = null, // Range: (0, 2]
        public ?int $seed = null, // OpenAI only. This feature is in Beta. If specified, our system will make a best effort to sample deterministically, such that repeated requests with the same seed and parameters should return the same result.

        // Function-calling
        /**
         * Only natively supported by OpenAI models. For others, we submit a YAML-formatted string with these tools at the end of the prompt.
         *
         * @var string|array|null
         */
        #[AllowedValues([ToolChoiceType::AUTO, ToolChoiceType::NONE])]
        public string|array|null $tool_choice = null, // none|auto or ToolCallData as {"type": "function", "function": {"name": "my_function"}}

        /**
         * Tool calls (also known as function calling) allow you to give an LLM access to external tools.
         *
         * @var ToolCallData[]|null
         */
        public ?array $tools = null,

        // Additional optional parameters
        /**
         * Modify the likelihood of specified tokens appearing in the completion. e.g. {"50256": -100}
         */
        public ?array $logit_bias = null,

        // OpenRouter-only parameters
        /**
         * See "Prompt Transforms" section: https://openrouter.ai/docs#transforms
         *
         * @var array|null
         */
        public ?array $transforms = null,

        /**
         * The models array, which lets you automatically try other models if the primary model's providers are down,
         * rate-limited, or refuse to reply due to content moderation required by all providers.
         *
         * @var array|null
         */
        public ?array $models = null,

        /**
         * @var string|null
         */
        #[AllowedValues([RouteType::FALLBACK])]
        public ?string $route = null,

        /**
         * See "Provider Routing" section: https://openrouter.ai/docs#provider-routing
         *
         * @var ProviderPreferencesData|null
         */
        public ?ProviderPreferencesData $provider = null,

        /**
         * Enable think tokens.
         * Default: false
         *
         * @var bool|null
         */
        public ?bool $include_reasoning = false,
    ) {
        $this->validateXorFields($this->messages, $this->prompt);
        $this->validateXorFields($this->model, $this->models);

        parent::__construct(...func_get_args());
    }

    /**
     * Validate the XOR fields and throw an exception if not valid.
     *
     * @param mixed $firstField
     * @param mixed $secondField
     *
     * @return void
     * @throws OpenRouterValidationException
     */
    private function validateXorFields(mixed $firstField, mixed $secondField): void
    {
        // Validate XOR fields
        $xorFields = new XORFields($firstField, $secondField);
        $validationResult = $xorFields->validate();

        // Throw an exception if the validation fails
        if (! $validationResult->isValid) {
            throw new OpenRouterValidationException($validationResult->message);
        }
    }

    /**
     * @return array
     */
    public function convertToArray(): array
    {
        return array_filter(
            [
                'messages'           => ! is_null($this->messages)
                    ? array_map(function ($value) {
                        if ($value instanceof MessageData) {
                            return $value->convertToArray();
                        } else {
                            return $value;
                        }
                    }, $this->messages)
                    : null,
                'prompt'             => $this->prompt,
                'model'              => $this->model,
                'response_format'    => $this->response_format?->convertToArray(),
                'usage'              => $this->usage ? ['include' => true] : null,
                'stop'               => $this->stop,
                'stream'             => $this->stream,
                'max_tokens'         => $this->max_tokens,
                'temperature'        => $this->temperature,
                'top_p'              => $this->top_p,
                'top_k'              => $this->top_k,
                'frequency_penalty'  => $this->frequency_penalty,
                'presence_penalty'   => $this->presence_penalty,
                'repetition_penalty' => $this->repetition_penalty,
                'seed'               => $this->seed,
                'tool_choice'        => $this->tool_choice,
                'tools'              => ! is_null($this->tools)
                    ? array_map(function ($value) {
                        if ($value instanceof ToolCallData) {
                            return $value->convertToArray();
                        } else {
                            return $value;
                        }
                    }, $this->tools)
                    : null,
                'logit_bias'         => $this->logit_bias,
                'transforms'         => $this->transforms,
                'models'             => $this->models,
                'route'              => $this->route,
                'provider'           => $this->provider?->convertToArray(),
                'include_reasoning'  => $this->include_reasoning,
            ],
            fn($value) => $value !== null
        );
    }
}
