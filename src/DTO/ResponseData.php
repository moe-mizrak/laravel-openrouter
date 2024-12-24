<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\LaravelData\Data as DataTransferObject;

/**
 * ResponseData is the general response DTO which consists of:
 * - id
 * - provider
 * - model
 * - object
 * - created
 * - choices (DTO object)
 * - usage (DTO object)
 *
 * Class ResponseData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class ResponseData extends DataTransferObject
{
    /**
     * @param string $id
     * @param string|null $provider
     * @param string $model
     * @param string $object
     * @param int $created
     * @param array|null $choices
     * @param UsageData|null $usage
     */
    public function __construct(
        /**
         * ID of the request which later can be used for cost request
         *
         * @var string
         */
        public string $id,

        /**
         * Model provider e.g. HuggingFace
         *
         * @var string|null
         */
        public ?string $provider = null,

        /**
         * Name of the model e.g. mistralai/mistral-7b-instruct:free
         *
         * @var string
         */
        public string $model,

        /**
         * e.g. 'chat.completion' | 'chat.completion.chunk'
         *
         * @var string
         */
        public string $object,

        /**
         * Unix timestamp of created_at e.g. 1715621307
         *
         * @var int
         */
        public int $created,

        /**
         * Depending on whether you set "stream" to "true"
         * and whether you passed in "messages" or a "prompt", you get a different output shape.
         *
         * @var StreamingChoiceData[]|NonStreamingChoiceData[]|NonChatChoiceData[]|null
         */
        public ?array $choices = null,

        /**
         * Usage information of api request.
         *
         * @var UsageData|null
         */
        public ?UsageData $usage = null
    ) {}
}