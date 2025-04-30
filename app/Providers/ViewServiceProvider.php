<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Auth;
use App\Models\Mainmenu as MainmenuDB;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $powerActions = config('menuSetting.powerActions');
        $powerActions = array_map(function($item) {
            return (object) $item;
        }, $powerActions);
        View::composer('admin.*', function ($view) use ($powerActions) {
            $mainmenus = MainmenuDB::with('submenu')->where(['is_on' => 1])->orderBy('sort','asc')->get();
            if(Auth::user()){
                $view->with('mainmenus', $mainmenus);
                $view->with('powerActions', $powerActions);
            }
        });
    }
}
