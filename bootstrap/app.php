<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'membership' => \App\Http\Middleware\CheckMembership::class,
            'admin_ukm' => \App\Http\Middleware\AdminUKMMiddleware::class,
            'block_admin_ukm_create' => \App\Http\Middleware\BlockAdminUkmCreate::class,
            'prevent-back' => \App\Http\Middleware\PreventBackHistory::class,
        ]);
        $middleware->appendToGroup('web', \App\Http\Middleware\SmartRedirect::class);
        $middleware->validateCsrfTokens(except: [
            'logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->call(function () {
            \App\Models\ActivityLog::where('created_at', '<', now()->subDays(7))->delete();
            \App\Models\LoginHistory::where('created_at', '<', now()->subDays(7))->delete();
        })->daily();
    })->create();
