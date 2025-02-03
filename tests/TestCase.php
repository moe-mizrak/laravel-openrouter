<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\Tests;

use MoeMizrak\LaravelOpenrouter\Facades\LaravelOpenRouter;
use MoeMizrak\LaravelOpenrouter\OpenRouterServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param $app
     *
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            OpenRouterServiceProvider::class,
        ];
    }

    /**
     * @param $app
     *
     * @return string[]
     */
    protected function getPackageAliases($app): array
    {
        return [
            'LaravelOpenRouter' => LaravelOpenRouter::class,
        ];
    }
}