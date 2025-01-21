<?php

declare(strict_types=1);

namespace App\Http\Resources;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: PostResource::class,
            ),
        ),
    ],
    required: ['data'],
)]
class PostCollection extends CustomResourceCollection {}
