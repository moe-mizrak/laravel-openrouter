<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Types;

/**
 * This class keeps data collection setting types.
 */
final readonly class DataCollectionType
{
    /**
     * allow: (default) allow providers which store user data non-transiently and may train on it.
     */
    const ALLOW = 'allow';

    /**
     * deny: use only providers which do not collect user data.
     */
    const DENY = 'deny';
}
