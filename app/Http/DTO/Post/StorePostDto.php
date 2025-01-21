<?php

declare(strict_types=1);

namespace App\Http\DTO\Post;

class StorePostDto
{
    public readonly string $content;

    public readonly int $user_id;

    /**
     * @param array{content: string, user_id: int} $input
     */
    public function __construct(array $input)
    {
        // @phpstan-ignore isset.offset
        if (!isset($input['content'])) {
            throw new \InvalidArgumentException('content is required');
        }

        // @phpstan-ignore isset.offset
        if (!isset($input['user_id'])) {
            throw new \InvalidArgumentException('user_id is required');
        }

        $this->content = $input['content'];
        $this->user_id = $input['user_id'];
    }

    /**
     * @return array{content: string, user_id: int}
     */
    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'user_id' => $this->user_id,
        ];
    }
}
