<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\UpdatePostController;
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
#[CoversClass(UpdatePostController::class)]
#[CoversMethod(UpdatePostController::class, '__invoke')]
final class UpdatePostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test投稿が更新されてステータスコードが200(): void
    {
        $beforeContent = Str::random();
        $afterContent = Str::random();

        $user = User::factory()->create();
        $post = Post::factory()->create([
            'content' => $beforeContent,
        ]);

        $response = $this->actingAs($user)->patchJson("/posts/{$post->id}", [
            'content' => $afterContent,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'content' => $afterContent,
        ]);
    }

    public function test未ログインの場合、投稿が更新されずステータスコードが401(): void
    {
        $beforeContent = Str::random();
        $afterContent = Str::random();

        User::factory()->create();
        $post = Post::factory()->create([
            'content' => $beforeContent,
        ]);

        $response = $this->patchJson("/posts/{$post->id}", [
            'content' => $afterContent,
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'content' => $beforeContent,
        ]);
    }

    public function test他のユーザーの投稿が更新できずステータスコードが403(): void
    {
        $beforeContent = Str::random();
        $afterContent = Str::random();

        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        /** @var Post */
        $post = $otherUser->posts()->save(Post::factory()->make([
            'content' => $beforeContent,
        ]));

        $response = $this->actingAs($user)->patchJson("/posts/{$post->id}", [
            'content' => $afterContent,
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas(Post::class, [
            'id' => $post->id,
            'content' => $beforeContent,
        ]);
    }

    public function test存在しない投稿を更新しようとした場合、ステータスコードが404(): void
    {
        $notExistsPostId = 123;
        $content = Str::random();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->patchJson("/posts/{$notExistsPostId}", [
            'content' => $content,
        ]);

        $response->assertStatus(404);

        $this->assertDatabaseMissing(Post::class, [
            'id' => $notExistsPostId,
        ]);
    }
}
