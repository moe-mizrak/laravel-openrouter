<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * DTO for the input audio which are data and format for the audio.
 */
final class InputAudioData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * base64 encoded audio data
         */
        public string $data,

        /**
         * Optional, detail about the audio format
         * Supported audio formats are: mp3, wav.
         */
        public ?string $format = null
    ) {
        parent::__construct(...func_get_args());
    }

    public function convertToArray(): array
    {
        return array_filter(
            [
                'data' => $this->data,
                'format' => $this->format,
            ],
            fn($value) => $value !== null
        );
    }
}
