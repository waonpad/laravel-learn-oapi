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
