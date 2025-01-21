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
            property: 'name',
            type: 'string',
        ),
        new OA\Property(
            property: 'email',
            type: 'string',
            format: 'email',
        ),
        new OA\Property(
            property: 'emailVerifiedAt',
            type: 'string',
        ),
    ],
    required: ['id', 'name', 'email', 'emailVerifiedAt'],
)]
class UserResource extends CustomJsonResource
{
    public int $id;

    public string $name;

    public string $email;

    public string $emailVerifiedAt;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'emailVerifiedAt' => $this->resource->email_verified_at,
        ];
    }
}
