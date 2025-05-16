<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Services\MainmenuService;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $powerActions = config('menuSetting.powerActions');
        $powerActions = array_map(fn($item) => (object) $item, $powerActions);

        View::composer('admin.*', function ($view) use ($powerActions) {
            if (Auth::check()) {
                // 呼叫 MainmenuService 的 get 方法 // 預設就有 with('submenu') 並已排序
                $mainmenus = app(MainmenuService::class)->get();
                $view->with('mainmenus', $mainmenus);
                $view->with('powerActions', $powerActions);
            }
        });
    }
}
