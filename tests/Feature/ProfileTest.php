<?php

declare(strict_types=1);

use function Pest\Laravel\get;

test('profile page displays personal information', function (): void {
    $testResponse = get('/');

    $testResponse->assertSuccessful();
    $testResponse->assertSee('Jan Willem Kaper', false);
    $testResponse->assertSee('Engineering Manager', false);
    $testResponse->assertSee('Paragin Group', false);
    $testResponse->assertSee('Amersfoort, The Netherlands', false);
    $testResponse->assertSee('Work', false);
    $testResponse->assertSee('Contact', false);
    $testResponse->assertSee('Remindo', false);
    $testResponse->assertSee('https://www.linkedin.com/in/jwkaper/', false);
    $testResponse->assertSee('kapersoft@gmail.com', false);
});

test('profile page returns not found for unknown routes', function (): void {
    get('/unknown-page')->assertNotFound();
});
