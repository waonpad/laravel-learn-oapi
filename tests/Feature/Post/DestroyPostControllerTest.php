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

    public function testステータスコードが204(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/posts/{$post->id}");

        $response->assertStatus(204);
    }

    public function testDBの対象レコードが削除される(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)->deleteJson("/posts/{$post->id}");

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
