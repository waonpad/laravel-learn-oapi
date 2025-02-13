<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
        ),
        new OA\Property(
            property: 'content',
            type: 'string',
        ),
        new OA\Property(
            property: 'userId',
            type: 'integer',
        ),
        new OA\Property(
            property: 'createdAt',
            type: 'string',
        ),
        new OA\Property(
            property: 'updatedAt',
            type: 'string',
        ),
    ],
    required: ['id', 'content', 'userId', 'createdAt', 'updatedAt'],
)]
class PostResource extends CustomJsonResource
{
    /** @var Post */
    public $resource;

    public function __construct(Post $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array|Arrayable|\JsonSerializable
    {
        return [
            'id' => $this->resource->id,
            'content' => $this->resource->content,
            'userId' => $this->resource->user_id,
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
        ];
    }
}
