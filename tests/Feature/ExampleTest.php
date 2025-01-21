<?php

declare(strict_types=1);

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\ExampleController
 *
 * @covers \App\Http\Controllers\ExampleController
 *
 * @internal
 */
final class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @covers ::index
     */
    public function testTheApplicationReturnsASuccessfulResponse(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
