<?php

namespace MoeMizrak\LaravelOpenrouter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use MoeMizrak\LaravelOpenrouter\Facades\LaravelOpenRouter;

class OpenRouterServiceProvider extends ServiceProvider
{
    /**
     * The default timeout for the Guzzle client.
     *
     * @var int
     */
    const DEFAULT_TIMEOUT = 20;

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

        $this->app->singleton(ClientInterface::class, function () {
            return $this->configureClient();
        });

        $this->app->bind('laravel-openrouter', function () {
            return new OpenRouterRequest();
        });

        $this->app->bind(OpenRouterRequest::class, function () {
            return $this->app->make('laravel-openrouter');
        });

        // Register the facade alias.
        AliasLoader::getInstance()->alias('LaravelOpenRouter', LaravelOpenRouter::class);
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
        // Set the default configuration for retrying requests
        $retryOptions = [
            'max_retry_attempts' => 5,
            'retry_on_status'    => [429, 500, 502, 503, 504],
            'retry_on_timeout'   => true,
        ];

        // Create a handler stack with the retry middleware.
        $handlerStack = HandlerStack::create();

        // Add the retry middleware to the handler stack.
        $handlerStack->push(GuzzleRetryMiddleware::factory($retryOptions));

        /*
         * Create and return a Guzzle client with the base_uri, timeout, headers and handler stack request options.
         * For more info: https://openrouter.ai/docs
         */
        return new Client([
            'base_uri' => config('laravel-openrouter.api_endpoint'),
            'timeout'  => config('laravel-openrouter.api_timeout', self::DEFAULT_TIMEOUT),
            'handler'  => $handlerStack,
            'headers'  => [
                'Authorization' => 'Bearer ' . config('laravel-openrouter.api_key'),
                'HTTP-Referer'  => 'https://github.com/moe-mizrak/laravel-openrouter',
                'X-Title'       => 'laravel-openrouter',
                'Content-Type'  => 'application/json',
            ],
        ]);
    }
}