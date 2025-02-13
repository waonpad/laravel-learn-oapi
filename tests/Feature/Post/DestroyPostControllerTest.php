<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\DestroyPostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

/**
 * @internal
 */
#[CoversClass(DestroyPostController::class)]
#[CoversMethod(DestroyPostController::class, '__invoke')]
final class DestroyPostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test投稿が削除されてステータスコードが204(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($author)->deleteJson("/posts/{$post->id}");

        $response->assertStatus(204);
        $this->assertModelMissing($post);
    }

    public function test未ログインの場合、投稿が削除されずステータスコードが401(): void
    {
        User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->deleteJson("/posts/{$post->id}");

        $response->assertStatus(401);
        $this->assertModelExists($post);
    }

    public function test他のユーザーの投稿が削除できずステータスコードが403(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();

        /** @var Post */
        $post = $otherUser->posts()->save(Post::factory()->make());

        $response = $this->actingAs($author)->deleteJson("/posts/{$post->id}");

        $response->assertStatus(403);
        $this->assertModelExists($post);
    }

    public function test存在しない投稿を削除しようとした場合、ステータスコードが404(): void
    {
        $notExistsPostId = 123;

        $author = User::factory()->create();

        $response = $this->actingAs($author)->deleteJson("/posts/{$notExistsPostId}");

        $response->assertStatus(404);
    }
}
