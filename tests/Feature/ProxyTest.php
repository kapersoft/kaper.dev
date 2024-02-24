<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\assertSame;

test('proxy can return pinkary profile', function () {
    // Arrange
    Http::fake([
        'https://pinkary.com/@kapersoft' => Http::response('pinkary profile', 200, ['Content-Type' => 'text/html; charset=UTF-8']),
    ]);

    // Act
    $response = $this->get('/');

    // Assert
    $response->assertStatus(200);
    $response->assertSee('pinkary profile');
    $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    assertSame([
        'body' => 'pinkary profile',
        'headers' => [
            'Content-Type' => [
                0 => 'text/html; charset=UTF-8',
            ],
        ],
    ], Cache::get('/'));
});

test('proxy can return pinkary asset', function () {
    // Arrange
    Http::fake([
        'https://pinkary.com/storage/avatars/ff01d2e6480cc91eb96b00949817b6ccf30940b999d2551a77a2feed5d61d7a8.png' => Http::response('pinkary profile picture', 200, ['Content-Type' => 'image/png']),
    ]);

    // Act
    $response = $this->get('/storage/avatars/ff01d2e6480cc91eb96b00949817b6ccf30940b999d2551a77a2feed5d61d7a8.png');

    // Assert
    $response->assertStatus(200);
    $response->assertSee('pinkary profile picture');
    $response->assertHeader('Content-Type', 'image/png');
    assertSame([
        'body' => 'pinkary profile picture',
        'headers' => [
            'Content-Type' => [
                0 => 'image/png',
            ],
        ],
    ], Cache::get('storage/avatars/ff01d2e6480cc91eb96b00949817b6ccf30940b999d2551a77a2feed5d61d7a8.png'));
});
