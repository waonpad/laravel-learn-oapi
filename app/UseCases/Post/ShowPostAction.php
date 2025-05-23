<?php

declare(strict_types=1);

namespace App\UseCases\Post;

use App\Models\Post;

class ShowPostAction
{
    public function __invoke(string $id): Post
    {
        return Post::findOrFail($id);
    }
}
