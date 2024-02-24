<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class ProxyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $url = Str::of($request->getRequestUri())
            ->when(
                fn (Stringable $url) => $url->is('/'),
                fn (Stringable $str) => $str->append('@'.Config::get('pinkary.username')),
            )
            ->prepend(Config::get('pinkary.base_url'))
            ->__toString();

        $response = Cache::remember($url, Config::get('pinkary.cache_ttl'), function () use ($url, $request) {
            $response = Http::get($url);

            $body = Str::of($response->body())
                ->replace([
                    Config::get('pinkary.base_url').'/img',
                    Config::get('pinkary.base_url').'/storage',
                    Config::get('pinkary.base_url').'/build',
                ],
                    [
                        $request->schemeAndHttpHost().'/img',
                        $request->schemeAndHttpHost().'/storage',
                        $request->schemeAndHttpHost().'/build',
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
