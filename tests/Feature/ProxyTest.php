<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\get;
use function PHPUnit\Framework\assertSame;

beforeEach(function (): void {
    Cache::clear();
    Config::set('pinkary.username', 'some-random-username');
});

test('proxy can return pinkary profile', function (): void {
    // Arrange
    $responseBody = <<<'HTML'
        <link rel="shortcut icon" href="https://pinkary.com/img/ico.svg">
        <meta property="og:url" content="https://pinkary.com/@kapersoft">
        <link rel="preload" as="style" href="https://pinkary.com/build/assets/app.css">
        <img src="https://pinkary.com/storage/avatars/some-random-username.png?foo=bar">
        <script>
            axios.post('https://pinkary.com/profile/timezone', { timezone })
        </script>
        <div class="tailwind-class" wire:click="click(1719)">
    HTML;
    Http::fake([
        'https://pinkary.com/@some-random-username' => Http::response($responseBody, 200, ['Content-Type' => 'text/html; charset=UTF-8']),
    ]);

    // Act
    $testResponse = get('/');

    // Assert
    $testResponse->assertStatus(200);
    $testResponse->assertSee('http://localhost/img/ico.svg');
    $testResponse->assertDontSee('https://pinkary.com/img/ico.svg');
    $testResponse->assertSee('http://localhost/build/assets/app.css');
    $testResponse->assertDontSee('https://pinkary.com/img/ico.svg');
    $testResponse->assertSee('http://localhost/storage/avatars/some-random-username.png?foo=bar');
    $testResponse->assertDontSee('https://pinkary.com/img/ico.svg');
    $testResponse->assertSee('https://pinkary.com/@kapersoft');
    $testResponse->assertDontSee('http://localhost/@kapersoft');
    $testResponse->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    assertSame([
        'body' => $responseBody = <<<'HTML'
            <link rel="shortcut icon" href="http://localhost/img/ico.svg">
            <meta property="og:url" content="https://pinkary.com/@kapersoft">
            <link rel="preload" as="style" href="http://localhost/build/assets/app.css">
            <img src="http://localhost/storage/avatars/some-random-username.png?foo=bar">
            <script>
                axios.post('http://localhost/profile/timezone', { timezone })
            </script>
            <div class="tailwind-class">
        HTML,
        'contentType' => 'text/html; charset=UTF-8',
    ], Cache::get('https://pinkary.com/@some-random-username'));
});

test('proxy can return pinkary asset', function (): void {
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
