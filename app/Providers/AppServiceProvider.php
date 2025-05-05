<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GoogleRecaptchaService;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
// 或使用：use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Google Recaptcha
        $this->app->singleton('GoogleRecaptcha', function ($app) {
            return new GoogleRecaptchaService();
        });

        // ImageManager 注冊
        $this->app->singleton(ImageManager::class, function () {
            return new ImageManager(new GdDriver()); // ✅ 改為傳入 Driver 實例
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
