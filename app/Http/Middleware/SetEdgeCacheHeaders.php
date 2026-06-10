<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetEdgeCacheHeaders
{
    public function __construct(
        private SetCacheHeaders $setCacheHeaders,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $options = sprintf(
            'public;max_age=%d;s_maxage=%d;etag',
            config('edge.max_age'),
            config('edge.s_maxage'),
        );

        return $this->setCacheHeaders->handle($request, $next, $options);
    }
}
