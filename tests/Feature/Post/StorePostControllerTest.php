<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\StorePostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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

    public function test投稿が作成されてステータスコードが201(): void
    {
        $content = Str::random();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/posts', [
            'content' => $content,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas(Post::class, [
            'content' => $content,
        ]);
    }

    public function test未ログインの場合、投稿が作成されずステータスコードが401(): void
    {
        $content = Str::random();

        User::factory()->create();

        $response = $this->postJson('/posts', [
            'content' => $content,
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing(Post::class, [
            'content' => $content,
        ]);
    }
}
