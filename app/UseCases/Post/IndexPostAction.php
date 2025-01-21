<?php

declare(strict_types=1);

namespace App\UseCases\Post;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexPostAction
{
    /**
     * Execute the action.
     *
     * @return LengthAwarePaginator<Post>
     */
    public function __invoke(): LengthAwarePaginator
    {
        return Post::paginate(10);
    }
}
