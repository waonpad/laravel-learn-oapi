<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\ShowPostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

/**
 * @internal
 */
#[CoversClass(ShowPostController::class)]
#[CoversMethod(ShowPostController::class, '__invoke')]
final class ShowPostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test投稿が取得され、ステータスコードが200(): void
    {
        User::factory()->create();
        /** @var object{created_at: Carbon, updated_at: Carbon}&Post */
        $post = Post::factory()->create();

        $response = $this->getJson("/posts/{$post->id}");

        $response->assertStatus(200);
        $response->assertExactJson([
            'id' => $post->id,
            'content' => $post->content,
            'userId' => $post->user_id,
            'createdAt' => $post->created_at->toISOString(),
            'updatedAt' => $post->updated_at->toISOString(),
        ]);
    }

    public function test存在しない投稿IDを指定した場合、ステータスコードが404(): void
    {
        $notExistsPostId = 123;

        $response = $this->getJson("/posts/{$notExistsPostId}");

        $this->assertJsonCommonErrorResponse($response, 404);
    }
}
