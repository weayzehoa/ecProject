<?php
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CompanySettingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PayMethodController;
use App\Http\Controllers\Admin\ShippingFeeController;
use App\Http\Controllers\Admin\AboutUsController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\FoodMenuController;

// 防止未定義的路由錯誤
Route::fallback(function () {
    return  redirect()->route('admin.dashboard');
});

Route::name('admin.')->group(function() {
    // 登入前的路由
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.submit');

    });
    // 登入後的路由
    Route::middleware('auth:admin')->group(function () {
        // 後台首頁（儀表板）
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // 公司資料設定
        Route::resource('companySettings', CompanySettingController::class);

        // 管理員帳號功能
        Route::post('admins/unlock/{id}', [AdminController::class, 'unlock'])->name('admins.unlock');
        Route::post('admins/active/{id}', [AdminController::class, 'active'])->name('admins.active');
        Route::resource('admins', AdminController::class);

        // 管理者操作紀錄
        Route::resource('adminLogs', AdminLogController::class);

        // 會員管理功能
        // Route::post('users/active/{id}', [UserController::class, 'active'])->name('users.active');
        // Route::get('users/sortup/{id}',[UserController::class, 'sortup'])->name('users.sortup');
        // Route::get('users/sortdown/{id}',[UserController::class, 'sortdown'])->name('users.sortdown');
        // Route::resource('users', UserController::class);

        // 商品管理
        // Route::resource('products', ProductController::class);

        // 商品分類
        // Route::resource('categories', CategoryController::class);

        // 訂單管理
        // Route::resource('orders', OrderController::class);

        // 付款方式設定
        // Route::resource('payMethods', PayMethodController::class);

        // 運費折扣設定
        // Route::resource('shippingFees', ShippingFeeController::class);

        // 輪播管理
        Route::post('banners/preview/{id}', [BannerController::class, 'preview'])->name('banners.preview');
        Route::post('banners/active/{id}', [BannerController::class, 'active'])->name('banners.active');
        Route::get('banners/sortup/{id}',[BannerController::class, 'sortup'])->name('banners.sortup');
        Route::get('banners/sortdown/{id}',[BannerController::class, 'sortdown'])->name('banners.sortdown');
        Route::resource('banners', BannerController::class);

        // 最新消息
        // Route::post('news/active/{id}', [NewsController::class, 'active'])->name('news.active');
        // Route::get('news/sortup/{id}',[NewsController::class, 'sortup'])->name('news.sortup');
        // Route::get('news/sortdown/{id}',[NewsController::class, 'sortdown'])->name('news.sortdown');
        // Route::resource('news', NewsController::class);

        // 分店資訊
        // Route::post('stores/active/{id}', [StoreController::class, 'active'])->name('stores.active');
        // Route::get('stores/sortup/{id}',[StoreController::class, 'sortup'])->name('stores.sortup');
        // Route::get('stores/sortdown/{id}',[StoreController::class, 'sortdown'])->name('stores.sortdown');
        // Route::resource('stores', StoreController::class);

        // 菜單資訊
        Route::post('foodmenus/preview/{id}', [FoodMenuController::class, 'preview'])->name('foodmenus.preview');
        Route::post('foodmenus/active/{id}', [FoodMenuController::class, 'active'])->name('foodmenus.active');
        Route::get('foodmenus/sortup/{id}',[FoodMenuController::class, 'sortup'])->name('foodmenus.sortup');
        Route::get('foodmenus/sortdown/{id}',[FoodMenuController::class, 'sortdown'])->name('foodmenus.sortdown');
        Route::resource('foodmenus', FoodMenuController::class);

        // 測試功能
        Route::get('testing', [DashboardController::class, 'testing'])->name('testing');

        // 登出
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    });

});

// 給 Laravel fallback 的 route('login')
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
