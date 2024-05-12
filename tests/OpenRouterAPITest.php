<?php

namespace MoeMizrak\LaravelOpenrouter\Tests;

use MoeMizrak\LaravelOpenrouter\OpenRouterRequest;

class OpenRouterAPITest extends TestCase
{
    private OpenRouterRequest $api;

    public function setUp(): void
    {
        parent::setUp();

        $this->api = $this->app->make(OpenRouterRequest::class);
    }

    /**
     * @test
     */
    public function it_successfully_tests_open_route_api_request()
    {
        /* SETUP */
        // TODO: params will be added here for testing the request

        /* EXECUTE */
        // params will be sent to testRequest method after access is granted and testRequest is modified.
        $response = $this->api->testRequest();

        /* ASSERT */
        // TODO: assertion for testRequest response
    }
}