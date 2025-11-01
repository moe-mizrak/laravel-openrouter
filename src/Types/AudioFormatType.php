<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Types;

/**
 * Audio can be provided in different formats for now: mp3, wav.
 * See: https://openrouter.ai/docs/features/multimodal/audio
 *
 * Class AudioFormatType
 * @package MoeMizrak\LaravelOpenrouter\Types
 */
final readonly class AudioFormatType
{
    /**
     * MP3 audio format
     *
     * @var string
     */
    const MP3 = 'mp3';

    /**
     * WAV audio format
     *
     * @var string
     */
    const WAV = 'wav';
}