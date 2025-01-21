<?php

declare(strict_types=1);

namespace OpenApi\SchemaDefinitions\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'message',
            type: 'string',
        ),
    ],
    required: ['message'],
)]
class BaseError {}

#[OA\Response(
    response: 401,
    description: '',
    content: new OA\JsonContent(ref: BaseError::class),
)]
class Unauthorized {}

#[OA\Response(
    response: 403,
    description: '',
    content: new OA\JsonContent(ref: BaseError::class),
)]
class Forbidden {}

#[OA\Response(
    response: 500,
    description: '',
    content: new OA\JsonContent(ref: BaseError::class),
)]
class InternalServerError {}
