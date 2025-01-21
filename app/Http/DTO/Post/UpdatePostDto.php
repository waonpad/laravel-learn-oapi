<?php

declare(strict_types=1);

namespace App\Http\DTO\Post;

class UpdatePostDto
{
    public readonly string $content;

    /**
     * @param array{content: string} $input
     */
    public function __construct($input)
    {
        // @phpstan-ignore isset.offset
        if (!isset($input['content'])) {
            throw new \InvalidArgumentException('content is required');
        }

        $this->content = $input['content'];
    }

    /**
     * @return array{content: string}
     */
    public function toArray(): array
    {
        return [
            'content' => $this->content,
        ];
    }
}
