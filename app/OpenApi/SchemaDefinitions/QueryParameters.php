<?php

declare(strict_types=1);

namespace OpenApi\SchemaDefinitions\QueryParameters;

use OpenApi\Attributes as OA;

#[OA\QueryParameter(
    name: self::NAME,
    parameter: self::REF_NAME,
    schema: new OA\Schema(type: 'integer'),
    required: false,
)]
class Page
{
    public const NAME = 'page';
    public const REF_NAME = 'Page';
    public const REF = '#/components/parameters/'.self::REF_NAME;
}
