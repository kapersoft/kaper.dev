<?php

use App\Http\Controllers\ProxyController;
use Illuminate\Support\Facades\Route;

Route::fallback(ProxyController::class);
