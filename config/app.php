<?php

declare(strict_types=1);

use Illuminate\Bus\BusServiceProvider;
use Illuminate\Cache\CacheServiceProvider;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider;
use Illuminate\Foundation\Providers\FoundationServiceProvider;
use Illuminate\View\ViewServiceProvider;

return [
    'providers' => [
        BusServiceProvider::class,
        CacheServiceProvider::class,
        DatabaseServiceProvider::class,
        ConsoleSupportServiceProvider::class,
        FilesystemServiceProvider::class,
        FoundationServiceProvider::class,
        ViewServiceProvider::class,
    ],
];
