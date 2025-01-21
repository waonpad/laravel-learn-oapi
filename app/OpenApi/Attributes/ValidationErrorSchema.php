<?php

declare(strict_types=1);

namespace OpenApi\Attributes;

use OpenApi\Generator;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS_CONSTANT)]
class ValidationErrorSchema extends Schema
{
    /**
     * @param list<string> $validationErrorProperties
     */
    public function __construct(
        array $validationErrorProperties,
        // schema
        // null|object|string $ref = null,
        ?string $schema = null,
        ?string $title = null,
        ?string $description = null,
        // ?int $maxProperties = null,
        // ?int $minProperties = null,
        // ?array $required = null,
        // ?array $properties = null,
        // null|array|string $type = null,
        // ?string $format = null,
        // ?OAA\Items $items = null,
        // ?string $collectionFormat = null,
        // mixed $default = Generator::UNDEFINED,
        // $maximum = null,
        // null|bool|float|int $exclusiveMaximum = null,
        // $minimum = null,
        // null|bool|float|int $exclusiveMinimum = null,
        // ?int $maxLength = null,
        // ?int $minLength = null,
        // ?int $maxItems = null,
        // ?int $minItems = null,
        // ?bool $uniqueItems = null,
        // ?string $pattern = null,
        // null|array|string $enum = null,
        // ?OAA\Discriminator $discriminator = null,
        ?bool $readOnly = null,
        ?bool $writeOnly = null,
        // ?OAA\Xml $xml = null,
        ?ExternalDocumentation $externalDocs = null,
        mixed $example = Generator::UNDEFINED,
        ?array $examples = null,
        // ?bool $nullable = null,
        ?bool $deprecated = null,
        // ?array $allOf = null,
        // ?array $anyOf = null,
        // ?array $oneOf = null,
        // null|bool|OAA\AdditionalProperties $additionalProperties = null,
        // mixed $const = Generator::UNDEFINED,
        // annotation
        ?array $x = null,
        ?array $attachables = null,
    ) {
        $properties = [
            new Property(
                property: 'message',
                type: 'string',
            ),
            new Property(
                property: 'errors',
                type: 'object',
                properties: array_map(
                    fn (string $validationErrorProperty) => new Property(property: $validationErrorProperty, type: 'string'),
                    $validationErrorProperties
                )
            ),
        ];

        $required = ['message', 'errors'];

        parent::__construct(
            // ref: $ref,
            schema: $schema,
            title: $title,
            description: $description,
            // maxProperties: $maxProperties,
            // minProperties: $minProperties,
            required: $required,
            properties: $properties,
            // type: $type,
            // format: $format,
            // items: $items,
            // collectionFormat: $collectionFormat,
            // default: $default,
            // maximum: $maximum,
            // exclusiveMaximum: $exclusiveMaximum,
            // minimum: $minimum,
            // exclusiveMinimum: $exclusiveMinimum,
            // maxLength: $maxLength,
            // minLength: $minLength,
            // maxItems: $maxItems,
            // minItems: $minItems,
            // uniqueItems: $uniqueItems,
            // pattern: $pattern,
            // enum: $enum,
            // discriminator: $discriminator,
            readOnly: $readOnly,
            writeOnly: $writeOnly,
            // xml: $xml,
            externalDocs: $externalDocs,
            example: $example,
            examples: $examples,
            // nullable: $nullable,
            deprecated: $deprecated,
            // allOf: $allOf,
            // anyOf: $anyOf,
            // oneOf: $oneOf,
            // additionalProperties: $additionalProperties,
            // const: $const,
            x: $x,
            attachables: $attachables,
        );
    }
}
