<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\DestroyPostController;
use App\Models\Post;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

#[CoversClass(DestroyPostController::class)]
#[CoversMethod(DestroyPostController::class, '__invoke')]
final class DestroyPostControllerTest extends TestCase
{
    public function test投稿が削除される(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($author)->deleteJson("/posts/{$post->id}");

        $response->assertStatus(204);
        $this->assertModelMissing($post);
    }

    public function test未ログインの場合、認証エラー(): void
    {
        User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->deleteJson("/posts/{$post->id}");

        $this->assertJsonCommonErrorResponse($response, 401);
    }

    public function test他のユーザーの投稿を削除しようとした場合、権限エラー(): void
    {
        $author = User::factory()->create();
        /** @var Post */
        $post = $author->posts()->save(Post::factory()->make());
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->deleteJson("/posts/{$post->id}");

        $this->assertJsonCommonErrorResponse($response, 403);
    }

    public function test存在しない投稿を削除しようとした場合、NotFoundエラー(): void
    {
        $notExistsPostId = rand();

        $author = User::factory()->create();

        $response = $this->actingAs($author)->deleteJson("/posts/{$notExistsPostId}");

        $this->assertJsonCommonErrorResponse($response, 404);
    }
}
