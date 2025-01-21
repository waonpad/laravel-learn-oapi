<?php

declare(strict_types=1);

namespace App\Http\Resources;

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
    ],
    required: ['id', 'content', 'userId'],
)]
class PostResource extends CustomJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'content' => $this->resource->content,
            'userId' => $this->resource->user_id,
        ];
    }
}
