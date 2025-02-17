<?php

declare(strict_types=1);

namespace App\Http\DTO\Post;

/**
 * @phpstan-import-type UpdatePostInput from \App\Http\Requests\Post\UpdatePostRequest
 */
class UpdatePostDto
{
    public readonly string $content;

    /**
     * @param UpdatePostInput $input
     */
    public function __construct(array $input)
    {
        // @phpstan-ignore isset.offset
        if (!isset($input['content'])) {
            throw new \InvalidArgumentException('content is required');
        }

        $this->content = $input['content'];
    }

    /**
     * @return UpdatePostInput
     */
    public function toArray(): array
    {
        return [
            'content' => $this->content,
        ];
    }
}
