<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\IndexPostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

/**
 * @internal
 */
#[CoversClass(IndexPostController::class)]
#[CoversMethod(IndexPostController::class, '__invoke')]
final class IndexPostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test正常(): void
    {
        User::factory()->create();
        Post::factory()->count(10)->create();

        $response = $this->getJson('/posts');

        $response->assertStatus(200);
    }
}
