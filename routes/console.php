<?php

declare(strict_types=1);

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

Artisan::command('validate-app-key', function () {
    /**
     * @var Command $command
     *
     * @phpstan-ignore variable.undefined
     */
    $command = $this;

    $command->comment('Validating APP_KEY...');

    $appKey = getenv('APP_KEY');

    if ($appKey === '') {
        $command->error('APP_KEY is not set');

        return 1;
    }

    $command->comment('APP_KEY is valid');

    return 0;
})->purpose('Validate APP_KEY')->daily();
