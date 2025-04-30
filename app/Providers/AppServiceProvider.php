<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GoogleRecaptchaService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('GoogleRecaptcha', function ($app) {
            return new GoogleRecaptchaService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $helperPath = app_path('Helpers/adminHelper.php');
        if (file_exists($helperPath)) {
            require_once $helperPath;
        }
    }
}
