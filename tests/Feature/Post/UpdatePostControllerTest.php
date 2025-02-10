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
        $id = 1;

        $user = User::factory()->create();
        Post::factory()->create([
            'id' => $id,
            'content' => Str::random(),
        ]);

        $response = $this->actingAs($user)->patchJson("/posts/{$id}", [
            'content' => Str::random(),
        ]);

        $response->assertStatus(200);
    }

    public function testDBの対象レコードが更新される(): void
    {
        $id = 1;
        $beforeContent = Str::random();
        $afterContent = Str::random();

        $user = User::factory()->create();
        Post::factory()->create([
            'id' => $id,
            'content' => $beforeContent,
        ]);

        $this->actingAs($user)->patchJson("/posts/{$id}", [
            'content' => $afterContent,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $id,
            'content' => $afterContent,
        ]);
    }
}
