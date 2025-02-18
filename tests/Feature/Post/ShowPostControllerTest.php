<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\ShowPostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

#[CoversClass(ShowPostController::class)]
#[CoversMethod(ShowPostController::class, '__invoke')]
final class ShowPostControllerTest extends TestCase
{
    public function test投稿が取得される(): void
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

    public function test存在しない投稿IDを指定した場合、NotFoundエラー(): void
    {
        $notExistsPostId = rand();

        $response = $this->getJson("/posts/{$notExistsPostId}");

        $this->assertJsonCommonErrorResponse($response, 404);
    }
}
