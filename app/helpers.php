<?php

declare(strict_types=1);

use Illuminate\Support\Str;

if (!function_exists('camelizeArrayRecursive')) {
    /**
     * Camelize all keys in an array recursively.
     *
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    function camelizeArrayRecursive(array $array): array
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results[Str::camel($key)] = camelizeArrayRecursive($value);
            } else {
                $results[Str::camel($key)] = $value;
            }
        }

        return $results;
    }
}
