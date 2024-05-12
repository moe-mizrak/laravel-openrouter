<?php

namespace MoeMizrak\LaravelOpenrouter;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class OpenRouterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPublishing();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->configure();

        $this->app->bind(OpenRouterRequest::class, function () {
            return new OpenRouterRequest(
                $this->configureClient()
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['laravel-openrouter'];
    }

    /**
     * Setup the configuration.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-openrouter.php', 'laravel-openrouter'
        );
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-openrouter.php' => config_path('laravel-openrouter.php'),
            ], 'laravel-openrouter');
        }
    }

    /**
     * Configure the Guzzle client.
     *
     * @return \GuzzleHttp\Client
     */
    private function configureClient(): Client
    {
        // todo open router client configuration options will be added here based on the documentation
        return new Client([
            'base_uri' => config('laravel-openrouter.api_endpoint'),
            'headers'  => [
                'Authorization' => 'Bearer ' . config('laravel-openrouter.api_key'),
                'content-type'      => 'application/json',
            ],
        ]);
    }
}