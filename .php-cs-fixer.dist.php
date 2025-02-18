<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(true)
    // PHP-CS-Fixer/PHP-CS-Fixer: A tool to automatically fix PHP Coding Standards issues
    // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer?tab=readme-ov-file#usage
    ->setRules([
        '@PhpCsFixer' => true,
        'declare_strict_types' => true,
        'yoda_style' => false,
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'case',
                'continue',
                'declare',
                'default',
                'exit',
                'goto',
                'include',
                'include_once',
                // PHPDocで型をつけたいだけなのに空行が入ると見た目が良くないため、除外
                // 'phpdoc',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
                'yield',
                'yield_from',
            ],
        ],
        // パッケージとして公開するわけではないため、不要なルールを無効化
        'php_unit_internal_class' => false,
    ])
    ->setFinder(
        Finder::create()
            // ここで対象のディレクトリを指定する
            // ->in([
            // __DIR__ . '/app',
            // ])
            ->in(__DIR__)
            ->exclude(['vendor', 'bootstrap/cache', 'storage'])
    )
;
