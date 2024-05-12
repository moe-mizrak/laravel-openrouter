<?php

namespace MoeMizrak\LaravelOpenrouter\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelOpenRouter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-openrouter';
    }
}