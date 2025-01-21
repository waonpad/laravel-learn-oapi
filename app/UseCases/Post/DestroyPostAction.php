<?php

declare(strict_types=1);

namespace App\UseCases\Post;

use App\Models\Post;

class DestroyPostAction
{
    /**
     * Execute the action.
     */
    public function __invoke(string $id): void
    {
        /** @var Post $post */
        $post = Post::findOrFail($id);
        $post->delete();
    }
}
