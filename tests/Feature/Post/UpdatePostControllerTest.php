<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\UpdatePostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test正常(): void
    {
        $id = 1;
        $beforeContent = 'content';
        $afterContent = 'updated content';

        $user = User::factory()->create();
        Post::factory()->create([
            'id' => $id,
            'content' => $beforeContent,
        ]);

        $response = $this->actingAs($user)->patchJson("/posts/{$id}", [
            'content' => $afterContent,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', [
            'id' => $id,
            'content' => $afterContent,
        ]);
    }
}
