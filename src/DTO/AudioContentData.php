<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;

/**
 * DTO for the audio contents.
 *
 * Class AudioContentData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class AudioContentData extends DataTransferObject
{
    /**
     * The allowed type for image content.
     */
    public const ALLOWED_TYPE = 'input_audio';

    /**
     * @inheritDoc
     */
    public function __construct(
        /**
         * Type of the content. (i.e. input_audio)
         *
         * @var string
         */
        #[AllowedValues([self::ALLOWED_TYPE])]
        public string $type = self::ALLOWED_TYPE,

        /**
         * DTO of input audio.
         *
         * @var InputAudioData
         */
        public InputAudioData $input_audio
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
                'type' => $this->type,
                'input_audio' => $this->input_audio?->convertToArray(),
            ],
            fn($value) => $value !== null
        );
    }
}