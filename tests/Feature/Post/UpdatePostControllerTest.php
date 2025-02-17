<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\UpdatePostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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

        $author = User::factory()->create();

        // 投稿の作成日時を固定
        $testCreatedAt = Carbon::now();
        Carbon::setTestNow($testCreatedAt);

        $post = Post::factory()->create([
            'content' => $beforeContent,
        ]);

        // 投稿の更新日時を固定
        $testUpdatedAt = $testCreatedAt->copy()->addDay();
        Carbon::setTestNow($testUpdatedAt);

        $response = $this->actingAs($author)->patchJson("/posts/{$post->id}", [
            'content' => $afterContent,
        ]);

        $response->assertStatus(200);
        $this->assertModelExists($post);
        $this->assertDatabaseHas(Post::class, [
            'content' => $afterContent,
            'created_at' => $testCreatedAt->toDateTimeString(),
            'updated_at' => $testUpdatedAt->toDateTimeString(),
        ]);
    }

    public function test更新した投稿が返却されてステータスコードが200(): void
    {
        $beforeContent = Str::random();
        $afterContent = Str::random();

        $author = User::factory()->create();
        $post = Post::factory()->create([
            'content' => $beforeContent,
        ]);

        $response = $this->actingAs($author)->patchJson("/posts/{$post->id}", [
            'content' => $afterContent,
        ]);

        /** @var object{created_at: Carbon, updated_at: Carbon}&Post */
        $updatedPost = Post::firstOrFail();

        $response->assertStatus(200);
        $response->assertExactJson([
            'id' => $post->id,
            'content' => $afterContent,
            'userId' => $author->id,
            'createdAt' => $updatedPost->created_at->toISOString(),
            'updatedAt' => $updatedPost->updated_at->toISOString(),
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

        $this->assertJsonCommonErrorResponse($response, 401);
        $this->assertModelExists($post);
        $this->assertDatabaseHas(Post::class, [
            'content' => $beforeContent,
        ]);
    }

    public function test他のユーザーの投稿が更新できずステータスコードが403(): void
    {
        $beforeContent = Str::random();
        $afterContent = Str::random();

        $author = User::factory()->create();
        /** @var Post */
        $post = $author->posts()->save(Post::factory()->make([
            'content' => $beforeContent,
        ]));
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->patchJson("/posts/{$post->id}", [
            'content' => $afterContent,
        ]);

        $this->assertJsonCommonErrorResponse($response, 403);
        $this->assertModelExists($post);
        $this->assertDatabaseHas(Post::class, [
            'content' => $beforeContent,
        ]);
    }

    public function test存在しない投稿を更新しようとした場合、ステータスコードが404(): void
    {
        $notExistsPostId = rand();
        $content = Str::random();

        $author = User::factory()->create();

        $response = $this->actingAs($author)->patchJson("/posts/{$notExistsPostId}", [
            'content' => $content,
        ]);

        $this->assertJsonCommonErrorResponse($response, 404);
    }
}
