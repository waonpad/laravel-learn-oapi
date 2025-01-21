<?php

declare(strict_types=1);

namespace OpenApi\SchemaDefinitions\PathParameters;

use OpenApi\Attributes as OA;

#[OA\PathParameter(
    name: self::NAME,
    parameter: self::REF_NAME,
    schema: new OA\Schema(
        type: 'string',
    ),
    required: true,
)]
class PostId
{
    public const NAME = 'id';
    public const REF_NAME = 'PostId';
    public const REF = '#/components/parameters/'.self::REF_NAME;
}
