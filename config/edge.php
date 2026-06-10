<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Edge Cache Headers
    |--------------------------------------------------------------------------
    |
    | Cache-Control directives for Laravel Cloud's edge network. The edge
    | cache is purged automatically on every deployment.
    |
    | @see https://cloud.laravel.com/docs/network#cache-control
    |
    */

    'max_age' => (int) env('EDGE_CACHE_MAX_AGE', 3600),

    // Maximum s-maxage Cloudflare honors; edge cache is purged on every deploy.
    's_maxage' => (int) env('EDGE_CACHE_S_MAXAGE', 31_536_000),

    'etag' => true,

];
