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

    public function testステータスコードが200(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->patchJson("/posts/{$post->id}", [
            'content' => Str::random(),
        ]);

        $response->assertStatus(200);
    }

    public function testDBの対象レコードが更新される(): void
    {
        $beforeContent = Str::random();
        $afterContent = Str::random();

        $user = User::factory()->create();
        $post = Post::factory()->create([
            'content' => $beforeContent,
        ]);

        $this->actingAs($user)->patchJson("/posts/{$post->id}", [
            'content' => $afterContent,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'content' => $afterContent,
        ]);
    }
}
