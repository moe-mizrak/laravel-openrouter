<?php

namespace MoeMizrak\LaravelOpenrouter\Types;

/**
 * This class keeps data collection setting types.
 *
 * Class DataCollectionType
 * @package MoeMizrak\LaravelOpenrouter\Types
 */
class DataCollectionType
{
    /**
     * allow: (default) allow providers which store user data non-transiently and may train on it.
     *
     * @var string
     */
    const ALLOW = 'allow';

    /**
     * deny: use only providers which do not collect user data.
     *
     * @var string
     */
    const DENY = 'deny';
}