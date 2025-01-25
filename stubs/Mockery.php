<?php

declare(strict_types=1);

use Mockery\MockInterface;

class Mockery
{
    /**
     * 型をIntelephenseに解釈させるためのスタブ.
     *
     * @see https://github.com/bmewburn/vscode-intelephense/issues/1784#issuecomment-1382667494
     *
     * @template T
     *
     * @param class-string<T> $arg
     *
     * @return MockInterface&T
     */
    public static function mock($arg) {}
}
