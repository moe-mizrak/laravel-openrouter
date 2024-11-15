<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Exceptions\XorValidationException;
use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;
use MoeMizrak\LaravelOpenrouter\Rules\XORFields;
use MoeMizrak\LaravelOpenrouter\Types\RouteType;
use MoeMizrak\LaravelOpenrouter\Types\ToolChoiceType;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * DTO for the chat completion request.
 *
 * Class ChatData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ChatData extends DataTransferObject
{
    /**
     * Constructor
     *
     * @param $params
     * @throws XorValidationException
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function __construct($params)
    {
        $this->validateXorFields($params);

        parent::__construct($params);
    }

    /**
     * Validate the XOR fields and throw an exception if not valid.
     *
     * @param array $params
     * @throws XorValidationException
     */
    private function validateXorFields(array $params): void
    {
        /**
         * Set the fields that have xor relation.
         */
        $xorFields = [
            ['messages', 'prompt'], // messages and prompt fields are XOR gated
            ['model', 'models'], // model and models fields are XOR gated
        ];

        // Loop through the xor fields and validate
        foreach ($xorFields as [$firstField, $secondField]) {
            $validator = new XORFields($firstField, $secondField);
            $validationResult = $validator->validate($params);

            // Throw exception in case validation is failed.
            if (!$validationResult->isValid) {
                throw new XorValidationException($validationResult->message);
            }
        }
    }

    /**
     * Message array consists of DTO data.
     * xor-gated with prompt field
     *
     * @var MessageData[]|null
     */
    public ?array $messages;

    /**
     * Prompt string data.
     * xor-gated with messages field
     *
     * @var string|null
     */
    public ?string $prompt;

    /**
     * Model name. If "model" is unspecified, uses the user's default.
     * For more info: https://openrouter.ai/docs#models
     *
     * @var string|null
     */
    public ?string $model;

    /**
     * The format of the output, e.g. json, text, srt, verbose_json ...
     *
     * @var ResponseFormatData|null
     */
    public ?ResponseFormatData $response_format;

    /**
     * Stop generation immediately if the model encounter any token specified in the stop array|string.
     *
     * @var array|string|null
     */
    public array|string|null $stop;

    /**
     * Enable streaming.
     *
     * @var bool|null
     */
    public ?bool $stream;

    /**
     * See LLM Parameters (https://openrouter.ai/docs#parameters) for following:
     */
    public ?int $max_tokens = 1024; // Range: [1, context_length) The maximum number of tokens that can be generated in the completion. Default 1024.
    public ?float $temperature; // Range: [0, 2] Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic.
    public ?float $top_p; // Range: (0, 1] An alternative to sampling with temperature, called nucleus sampling, where the model considers the results of the tokens with top_p probability mass.
    public ?float $top_k; // Range: [1, Infinity) Not available for OpenAI models
    public ?float $frequency_penalty; // Range: [-2, 2] Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model's likelihood to repeat the same line verbatim.
    public ?float $presence_penalty; // Range: [-2, 2] Positive values penalize new tokens based on whether they appear in the text so far, increasing the model's likelihood to talk about new topics.
    public ?float $repetition_penalty; // Range: (0, 2]
    public ?int $seed; // OpenAI only. This feature is in Beta. If specified, our system will make a best effort to sample deterministically, such that repeated requests with the same seed and parameters should return the same result.

    // Function-calling
    /**
     * Only natively supported by OpenAI models. For others, we submit a YAML-formatted string with these tools at the end of the prompt.
     *
     * @var string|array|null
     */
    #[AllowedValues([ToolChoiceType::AUTO, ToolChoiceType::NONE])]
    public string|array|null $tool_choice; // none|auto or ToolCallData as {"type": "function", "function": {"name": "my_function"}}

    /**
     * Tool calls (also known as function calling) allow you to give an LLM access to external tools.
     *
     * @var ToolCallData[]|null
     */
    public ?array $tools;

    // Additional optional parameters
    /**
     * Modify the likelihood of specified tokens appearing in the completion. e.g. {"50256": -100}
     */
    public ?array $logit_bias;

    // OpenRouter-only parameters
    /**
     * See "Prompt Transforms" section: https://openrouter.ai/docs#transforms
     *
     * @var array|null
     */
    public ?array $transforms;

    /**
     * The models array, which lets you automatically try other models if the primary model's providers are down,
     * rate-limited, or refuse to reply due to content moderation required by all providers.
     *
     * @var array|null
     */
    public ?array $models;

    /**
     * @var string|null
     */
    #[AllowedValues([RouteType::FALLBACK])]
    public ?string $route;

    /**
     * See "Provider Routing" section: https://openrouter.ai/docs#provider-routing
     *
     * @var ProviderPreferencesData|null
     */
    public ?ProviderPreferencesData $provider;
}