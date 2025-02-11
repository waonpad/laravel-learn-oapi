<?php

declare(strict_types=1);

namespace App\UseCases\Post;

use App\Http\DTO\Post\UpdatePostDto;
use App\Models\Post;

class UpdatePostAction
{
    public function __invoke(string $id, UpdatePostDto $input): Post
    {
        /** @var Post */
        $post = Post::findOrFail($id);

        $post->fill($input->toArray())->save();

        return $post;
    }
}
