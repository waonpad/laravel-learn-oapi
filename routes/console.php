<?php

declare(strict_types=1);

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    /**
     * @var Command $command
     *
     * @phpstan-ignore variable.undefined
     */
    $command = $this;

    $command->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
