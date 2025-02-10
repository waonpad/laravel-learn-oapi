<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\StorePostController;
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

    public function testステータスコードが201(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/posts', [
            'content' => Str::random(),
        ]);

        $response->assertStatus(201);
    }

    public function testDBにレコードが追加される(): void
    {
        $user = User::factory()->create();

        $content = Str::random();

        $this->actingAs($user)->postJson('/posts', [
            'content' => $content,
        ]);

        $this->assertDatabaseHas('posts', [
            'content' => $content,
        ]);
    }
}
