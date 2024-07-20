<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class ProxyController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $url = Str::of($request->getRequestUri())
            ->when(
                static fn (Stringable $stringable) => $stringable->is('/'),
                static fn (Stringable $stringable) => $stringable->append('@'.Config::get('pinkary.username')),
            )
            ->prepend(Config::get('pinkary.base_url'))
            ->__toString();

        $response = Cache::remember($url, Config::get('pinkary.cache_ttl'), static function () use ($url, $request): array {
            $response = Http::get($url);
            $body = Str::of($response->body())
                ->replace([
                    Config::get('pinkary.base_url').'/img',
                    Config::get('pinkary.base_url').'/storage',
                    Config::get('pinkary.base_url').'/build',
                    Config::get('pinkary.base_url').'/fonts',
                    Config::get('pinkary.base_url').'/profile',
                ],
                    [
                        $request->schemeAndHttpHost().'/img',
                        $request->schemeAndHttpHost().'/storage',
                        $request->schemeAndHttpHost().'/build',
                        $request->schemeAndHttpHost().'/fonts',
                        $request->schemeAndHttpHost().'/profile',
                    ])
                ->__toString();

            return [
                'body' => $body,
                'contentType' => $response->header('Content-Type'),
            ];
        });

        return Response::make($response['body'], 200, [
            'Content-Type' => $response['contentType'],
        ]);
    }
}
