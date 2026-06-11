<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;

final class AudioContentData extends DataTransferObject
{
    /**
     * The allowed type for image content.
     */
    public const ALLOWED_TYPE = 'input_audio';

    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * Type of the content. (i.e. input_audio)
         */
        #[AllowedValues([self::ALLOWED_TYPE])]
        public string $type = self::ALLOWED_TYPE,

        /**
         * DTO of input audio.
         */
        public InputAudioData $input_audio
    ) {
        parent::__construct(...func_get_args());
    }

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
