<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Tests;

use MoeMizrak\LaravelOpenrouter\Facades\LaravelOpenRouter;
use MoeMizrak\LaravelOpenrouter\OpenRouterServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            OpenRouterServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'LaravelOpenRouter' => LaravelOpenRouter::class,
        ];
    }
}