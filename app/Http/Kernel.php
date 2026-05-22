<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,

        // 🔥 TAMBAHKAN DI SINI
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'membership' => \App\Http\Middleware\CheckMembership::class,
    ];
}