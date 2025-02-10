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
        $id = 1;

        $user = User::factory()->create();
        Post::factory()->create([
            'id' => $id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/posts/{$id}");

        $response->assertStatus(204);
    }

    public function testDBの対象レコードが削除される(): void
    {
        $id = 1;

        $user = User::factory()->create();
        Post::factory()->create([
            'id' => $id,
        ]);

        $this->actingAs($user)->deleteJson("/posts/{$id}");

        $this->assertDatabaseMissing('posts', [
            'id' => $id,
        ]);
    }
}
