<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()->withPaths([
    __DIR__.'/app',
    __DIR__.'/bootstrap',
    __DIR__.'/config',
    __DIR__.'/public',
    __DIR__.'/routes',
    __DIR__.'/tests',
    __DIR__.'/rector.php',
])->withSkip([
    __DIR__.'/bootstrap/cache/*',
])->withSets([
    LevelSetList::UP_TO_PHP_83,
    LaravelSetList::LARAVEL_100,
    LaravelSetList::LARAVEL_CODE_QUALITY,
]);
