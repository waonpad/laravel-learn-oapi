<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\ShowPostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function testステータスコードが200(): void
    {
        User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->getJson("/posts/{$post->id}");

        $response->assertStatus(200);
    }
}
