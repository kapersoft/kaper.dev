<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;

uses(
    Tests\TestCase::class,
)->in('Feature');

uses()->beforeEach(fn () => Http::preventStrayRequests())->in(__DIR__);
