<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

class ProfileController
{
    /**
     * Display the personal profile page.
     */
    public function __invoke(): View
    {
        return view('profile', [
            'profile' => Config::get('profile'),
        ]);
    }
}
