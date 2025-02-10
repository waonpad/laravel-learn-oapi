<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\StorePostController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

/**
 * @internal
 */
#[CoversClass(StorePostController::class)]
#[CoversMethod(StorePostController::class, '__invoke')]
final class StorePostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test正常(): void
    {
        $content = 'content';

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/posts', [
            'content' => $content,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('posts', [
            'content' => $content,
        ]);
    }
}
