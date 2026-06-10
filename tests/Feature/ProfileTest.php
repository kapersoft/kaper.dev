<?php

declare(strict_types=1);

use function Pest\Laravel\get;

test('profile page is cacheable at the edge', function (): void {
    $testResponse = get('/');

    $testResponse->assertSuccessful();
    $testResponse->assertHeaderMissing('Set-Cookie');

    $cacheControl = $testResponse->headers->get('Cache-Control');

    expect($cacheControl)
        ->toContain('public')
        ->toContain('max-age='.config('edge.max_age'))
        ->toContain('s-maxage='.config('edge.s_maxage'));

    $testResponse->assertHeader('ETag');
});

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

test('health endpoint is cacheable at the edge', function (): void {
    $testResponse = get('/up');

    $testResponse->assertSuccessful();
    $testResponse->assertHeaderMissing('Set-Cookie');

    $cacheControl = $testResponse->headers->get('Cache-Control');

    expect($cacheControl)
        ->toContain('public')
        ->toContain('max-age='.config('edge.max_age'))
        ->toContain('s-maxage='.config('edge.s_maxage'));

    $testResponse->assertHeader('ETag');
});
