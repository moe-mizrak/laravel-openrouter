<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Types;

/**
 * This class keeps quantization level types for provider filtering.
 * For more info: https://openrouter.ai/docs/guides/routing/provider-selection
 *
 * Class QuantizationType
 * @package MoeMizrak\LaravelOpenrouter\Types
 */
final readonly class QuantizationType
{
    /**
     * 4-bit integer quantization.
     *
     * @var string
     */
    const INT4 = 'int4';

    /**
     * 8-bit integer quantization.
     *
     * @var string
     */
    const INT8 = 'int8';

    /**
     * 4-bit floating point quantization.
     *
     * @var string
     */
    const FP4 = 'fp4';

    /**
     * 6-bit floating point quantization.
     *
     * @var string
     */
    const FP6 = 'fp6';

    /**
     * 8-bit floating point quantization.
     *
     * @var string
     */
    const FP8 = 'fp8';

    /**
     * 16-bit floating point (half precision).
     *
     * @var string
     */
    const FP16 = 'fp16';

    /**
     * 16-bit brain floating point.
     *
     * @var string
     */
    const BF16 = 'bf16';

    /**
     * 32-bit floating point (full precision).
     *
     * @var string
     */
    const FP32 = 'fp32';

    /**
     * Unknown quantization level.
     *
     * @var string
     */
    const UNKNOWN = 'unknown';
}
