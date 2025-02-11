<?php

declare(strict_types=1);

namespace App\UseCases\Post;

use App\Http\DTO\Post\StorePostDto;
use App\Models\Post;

class StorePostAction
{
    /**
     * Execute the action.
     */
    public function __invoke(StorePostDto $input): Post
    {
        return Post::create($input->toArray());
    }
}
