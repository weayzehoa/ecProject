<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // 自動載入 app/Helpers 目錄下所有 *.php
        foreach (glob(app_path('Helpers') . '/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
