<?php

declare(strict_types=1);

use App\Http\Controllers\ProxyController;
use Illuminate\Support\Facades\Route;

Route::fallback(ProxyController::class);
