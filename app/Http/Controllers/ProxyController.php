<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class ProxyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if ($request->path() === '/') {
            $url = 'https://pinkary.com/@kapersoft';
        } else {
            $url = "https://pinkary.com/{$request->path()}";
        }

        ['body' => $body, 'headers' => $headers] = Cache::remember(
            $request->path(),
            now()->addHour(),
            function () use ($url) {
                $response = Http::get($url);

                return [
                    'body' => $response->body(),
                    'headers' => $response->headers(),
                ];
            }
        );

        $body = (string) str($body)->replace('https://pinkary.com', $request->schemeAndHttpHost());

        return Response::make($body, 200, [
            'Content-Type' => $headers['Content-Type'] ?? 'text/html',
        ]);
    }
}
