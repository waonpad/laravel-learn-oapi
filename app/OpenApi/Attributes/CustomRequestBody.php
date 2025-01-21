<?php

declare(strict_types=1);

namespace OpenApi\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER | \Attribute::IS_REPEATABLE)]
class CustomRequestBody extends RequestBody
{
    public function __construct(
        null|object|string $ref = null,
        ?string $request = null,
        ?string $description = null,
        ?bool $required = null,
        null|array|Attachable|JsonContent|MediaType|XmlContent $content = null,
        // annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct(
            ref: $ref,
            request: $request,
            description: $description,
            required: $required,
            content: $content,
            x: $x,
            attachables: $attachables,
        );
    }
}
