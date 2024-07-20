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
        $url = (string) Str::of($request->getRequestUri())
            ->when(
                static fn (Stringable $stringable) => $stringable->is('/'),
                static fn (Stringable $stringable) => $stringable->append('@'.Config::get('pinkary.username')),
            )
            ->prepend(Config::get('pinkary.base_url'));

        $response = Cache::remember($url, Config::get('pinkary.cache_ttl'), function () use ($url, $request): array {
            $response = Http::get($url);
            $body = (string) $response->body();
            $body = $this->replacePinkaryDomainWithHostDomain($body, $request);

            return [
                'body' => $body,
                'contentType' => $response->header('Content-Type'),
            ];
        });

        return Response::make($response['body'], 200, [
            'Content-Type' => $response['contentType'],
        ]);
    }

    private function replacePinkaryDomainWithHostDomain(string $body, Request $request): string
    {
        $paths = [
            '/img',
            '/storage',
            '/build',
            '/fonts',
            '/profile',
        ];
        $wireClickPattern = '/\s*wire:click="[^"]*"/';

        return (string) Str::of($body)
            ->replace(
                array_map(fn (string $path): string => Config::get('pinkary.base_url').$path, $paths),
                array_map(fn (string $path): string => $request->schemeAndHttpHost().$path, $paths),
            )
            ->replaceMatches($wireClickPattern, '');
    }
}
