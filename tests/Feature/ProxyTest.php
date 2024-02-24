<?php

use function PHPUnit\Framework\assertSame;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::clear();
    Config::set('pinkary.username', 'some-random-username');
});

test('proxy can return pinkary profile', function () {
    // Arrange
    Http::fake([
        'https://pinkary.com/@some-random-username' => Http::response('pinkary profile', 200, ['Content-Type' => 'text/html; charset=UTF-8']),
    ]);

    // Act
    $response = $this->get('/');

    // Assert
    $response->assertStatus(200);
    $response->assertSee('pinkary profile');
    $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    assertSame([
        'body' => 'pinkary profile',
        'contentType' => 'text/html; charset=UTF-8',
    ], Cache::get('https://pinkary.com/@some-random-username'));
});

test('proxy can return pinkary asset', function () {
    // Arrange
    Http::fake([
        'https://pinkary.com/storage/avatars/some-random-username.png?foo=bar' => Http::response('pinkary profile picture', 200, ['Content-Type' => 'image/png']),
    ]);

    // Act
    $response = $this->get('/storage/avatars/some-random-username.png?foo=bar');

    // Assert
    $response->assertStatus(200);
    $response->assertSee('pinkary profile picture');
    $response->assertHeader('Content-Type', 'image/png');
    assertSame([
        'body' => 'pinkary profile picture',
        'contentType' => 'image/png',
    ], Cache::get('https://pinkary.com/storage/avatars/some-random-username.png?foo=bar'));
});
