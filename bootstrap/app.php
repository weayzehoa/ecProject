<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Providers\RouteServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
->withRouting(function () {
        // ✅ Web 前台 domain
        Route::middleware('web')
            ->domain(env('WEB_DOMAIN', 'web.localhost'))
            ->group(base_path('routes/web.php'));

        // ✅ Admin 後台 domain
        Route::middleware('web')
            ->domain(env('ADMIN_DOMAIN', 'admin.localhost'))
            ->group(base_path('routes/admin.php'));

        // ✅ API domain
        Route::middleware('api')
            ->domain(env('API_DOMAIN', 'api.localhost'))
            ->group(base_path('routes/api.php'));
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'checkPermission' => \App\Http\Middleware\CheckPermissionAccess::class,
        ]);
    })
    ->withProviders([
        \App\Providers\ViewServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
