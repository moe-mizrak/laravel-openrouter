<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * Allows to force the model to produce specific output format.
 * Only supported by OpenAI models, Nitro models, and some others - check the
 *  providers on the model page on https://openrouter.ai/docs#models to see if it's supported,
 *  and set `require_parameters` to true in your Provider Preferences. See
 *  https://openrouter.ai/docs#provider-routing
 *
 * Class ResponseFormatData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class ResponseFormatData extends DataTransferObject
{
    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * The format of the output, e.g. json, text, srt, verbose_json ...
         *
         * @var string
         */
        public string $type,

        /**
         * The JSON schema for the output format.
         *
         * @var mixed
         */
        public mixed $json_schema = null
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
                'type'        => $this->type,
                'json_schema' => $this->json_schema,
            ],
            fn($value) => $value !== null
        );
    }
}