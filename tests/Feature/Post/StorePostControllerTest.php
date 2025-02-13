<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\StorePostController;
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
#[CoversClass(StorePostController::class)]
#[CoversMethod(StorePostController::class, '__invoke')]
final class StorePostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test投稿が作成されてステータスコードが201(): void
    {
        $content = Str::random();
        $author = User::factory()->create();
        // 投稿の作成,更新日時を固定
        Carbon::setTestNow(Carbon::now());

        $response = $this->actingAs($author)->postJson('/posts', [
            'content' => $content,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas(Post::class, [
            'content' => $content,
            'user_id' => $author->id,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    public function test作成した投稿が返却されてステータスコードが201(): void
    {
        $content = Str::random();

        $author = User::factory()->create();

        $response = $this->actingAs($author)->postJson('/posts', [
            'content' => $content,
        ]);

        /** @var object{created_at: Carbon, updated_at: Carbon}&Post */
        $createdPost = Post::firstOrFail();

        $response->assertStatus(201);
        $response->assertExactJson([
            'id' => $createdPost->id,
            'content' => $content,
            'userId' => $author->id,
            'createdAt' => $createdPost->created_at->toISOString(),
            'updatedAt' => $createdPost->updated_at->toISOString(),
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
        $this->assertCommonErrorJsonResponse($response);
        $this->assertDatabaseEmpty(Post::class);
    }
}
