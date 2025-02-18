<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Http\Controllers\Post\IndexPostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

#[CoversClass(IndexPostController::class)]
#[CoversMethod(IndexPostController::class, '__invoke')]
final class IndexPostControllerTest extends TestCase
{
    public function test投稿のリストが取得される(): void
    {
        $postsCount = 10;
        $page = 1;
        $perPage = 10;
        $from = ($page - 1) * $perPage + 1;

        $author = User::factory()->create();
        Post::factory()->count($postsCount)->create();

        $response = $this->getJson('/posts');

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json): AssertableJson => $json
                ->has(
                    'data',
                    $perPage,
                    fn (AssertableJson $json): AssertableJson => $json
                        ->whereType('id', 'integer')
                        ->whereType('content', 'string')
                        ->where('userId', $author->id)
                        ->whereType('createdAt', 'string')
                        ->whereType('updatedAt', 'string')
                )
                // @phpstan-ignore binaryOp.invalid
                ->where('links.first', config('app.url').'/posts?page=1')
                // @phpstan-ignore binaryOp.invalid
                ->where('links.last', config('app.url').'/posts?page='.ceil($postsCount / $perPage))
                ->where('links.prev', null)
                ->where('links.next', null)
                ->where('meta.currentPage', $page)
                ->where('meta.from', $from)
                ->where('meta.lastPage', (int) ceil($postsCount / $perPage))
                ->has(
                    'meta.links',
                    3,
                    fn (AssertableJson $json): AssertableJson => $json
                        ->whereType('url', 'string|null')
                        ->whereType('label', 'string')
                        ->whereType('active', 'boolean')
                )
                // @phpstan-ignore binaryOp.invalid
                ->where('meta.path', config('app.url').'/posts')
                ->where('meta.perPage', $perPage)
                ->where('meta.to', $from + $perPage - 1)
                ->where('meta.total', $postsCount)
        );
    }

    public function test2ページ目の投稿のリストが取得される(): void
    {
        $postsCount = 30;
        $page = 2;
        $perPage = 10;
        $from = ($page - 1) * $perPage + 1;

        $author = User::factory()->create();
        Post::factory()->count($postsCount)->create();

        $response = $this->getJson("/posts?page={$page}");

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json): AssertableJson => $json
                ->has(
                    'data',
                    $perPage,
                    fn (AssertableJson $json): AssertableJson => $json
                        ->whereType('id', 'integer')
                        ->whereType('content', 'string')
                        ->where('userId', $author->id)
                        ->whereType('createdAt', 'string')
                        ->whereType('updatedAt', 'string')
                )
                // @phpstan-ignore binaryOp.invalid
                ->where('links.first', config('app.url').'/posts?page=1')
                // @phpstan-ignore binaryOp.invalid
                ->where('links.last', config('app.url').'/posts?page='.ceil($postsCount / $perPage))
                // @phpstan-ignore binaryOp.invalid
                ->where('links.prev', config('app.url').'/posts?page='.($page - 1))
                // @phpstan-ignore binaryOp.invalid
                ->where('links.next', config('app.url').'/posts?page='.($page + 1))
                ->where('meta.currentPage', $page)
                ->where('meta.from', $from)
                ->where('meta.lastPage', (int) ceil($postsCount / $perPage))
                ->has(
                    'meta.links',
                    5,
                    fn (AssertableJson $json): AssertableJson => $json
                        ->whereType('url', 'string|null')
                        ->whereType('label', 'string')
                        ->whereType('active', 'boolean')
                )
                // @phpstan-ignore binaryOp.invalid
                ->where('meta.path', config('app.url').'/posts')
                ->where('meta.perPage', $perPage)
                ->where('meta.to', $from + $perPage - 1)
                ->where('meta.total', $postsCount)
        );
    }
}
