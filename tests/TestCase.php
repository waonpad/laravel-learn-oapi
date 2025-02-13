<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param TestResponse<JsonResponse> $response
     */
    public function assertJsonCommonErrorResponse(TestResponse $response): static
    {
        $response->assertJson(
            fn (AssertableJson $json): AssertableJson => $json
                ->whereType('message', 'string')
        );

        return $this;
    }

    /**
     * @param TestResponse<JsonResponse> $response
     */
    public function assertJsonValidationErrorsResponse(TestResponse $response): static
    {
        $response->assertJson(
            fn (AssertableJson $json): AssertableJson => $json
                ->whereType('message', 'string')
                ->whereType('errors', 'array')
        );

        return $this;
    }
}
