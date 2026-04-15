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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'webhooks/paymongo',
        ]);
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        $schedule->command('appointments:send-reminders')->everyFifteenMinutes();
        $schedule->command('appointments:mark-no-show')->hourly();
        $schedule->command('payments:check-pending')->everyThirtyMinutes();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
