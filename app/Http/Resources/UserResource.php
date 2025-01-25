<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
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
    /** @var User */
    public $resource;

    public function __construct(User $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array|Arrayable|\JsonSerializable
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'emailVerifiedAt' => $this->resource->email_verified_at,
        ];
    }
}
