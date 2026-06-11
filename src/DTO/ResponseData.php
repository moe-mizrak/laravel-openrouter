<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * ResponseData is the general response DTO which consists of:
 * - id
 * - model
 * - object
 * - created
 * - provider
 * - citations
 * - choices (DTO object)
 * - usage (DTO object)
 */
final class ResponseData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * ID of the request which later can be used for cost request
         */
        public string $id,

        /**
         * Name of the model e.g. mistralai/mistral-7b-instruct:free
         */
        public string $model,

        /**
         * e.g. 'chat.completion' | 'chat.completion.chunk'
         */
        public string $object,

        /**
         * Unix timestamp of created_at e.g. 1715621307
         */
        public int $created,

        /**
         * Model provider e.g. HuggingFace
         */
        public ?string $provider = null,

        /**
         * If using Perplexity Sonar, will return citations
         *
         * @var string[]|null
         */
        public ?array $citations = null,

        /**
         * Depending on whether you set "stream" to "true"
         * and whether you passed in "messages" or a "prompt", you get a different output shape.
         *
         * @var StreamingChoiceData[]|NonStreamingChoiceData[]|NonChatChoiceData[]|null
         */
        public ?array $choices = null,

        /**
         * Usage information of api request.
         */
        public ?UsageData $usage = null,
    ) {
        parent::__construct(...func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_filter(
            [
                'id' => $this->id,
                'model' => $this->model,
                'object' => $this->object,
                'created' => $this->created,
                'provider' => $this->provider,
                'citations' => $this->citations,
                'choices' => $this->choices,
                'usage' => $this->usage?->toArray(),
            ],
            fn($value) => $value !== null
        );
    }
}
