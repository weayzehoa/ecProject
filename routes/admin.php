<?php
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CompanySettingController;
use App\Http\Controllers\Admin\AdminController;
// use App\Http\Controllers\Admin\AdminLogController;
// use App\Http\Controllers\Admin\MenuController;
// use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Admin\ContactUsController;
// use App\Http\Controllers\Admin\OrderController;
// use App\Http\Controllers\Admin\ShippingFeeController;
// use App\Http\Controllers\Admin\PayMethodController;

// 防止未定義的路由錯誤
Route::fallback(function () {
    return  redirect()->route('admin.dashboard');
});

Route::name('admin.')->group(function() {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.submit');

    });

    Route::middleware('auth:admin')->group(function () {
        // 後台首頁（儀表板）
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        //公司資料設定
        Route::resource('companySettings', CompanySettingController::class);

        //系統參數設定
        // Route::resource('systemSettings', SystemSettingController::class);

        //管理員帳號功能
        Route::post('admins/unlock/{id}', [AdminController::class, 'unlock'])->name('admins.unlock');
        Route::post('admins/active/{id}', [AdminController::class, 'active'])->name('admins.active');
        Route::resource('admins', AdminController::class);

        //管理員操作紀錄功能
        // Route::resource('adminLogs', AdminLogController::class);

        //會員管理功能
        // Route::post('users/active/{id}', [UserController::class, 'active']);
        // Route::get('users/sortup/{id}',[UserController::class, 'sortup']);
        // Route::get('users/sortdown/{id}',[UserController::class, 'sortdown']);
        // Route::resource('users', UserController::class);

        //聯絡我們功能
        // Route::resource('contactUs', ContactUsController::class);

        //訂單管理功能
        // Route::resource('orders', OrderController::class);

        //付款方式功能
        // Route::resource('payMethods', PayMethodController::class);

        //運費折價功能
        // Route::resource('shippingFees', ShippingFeeController::class);


        //後台主選單管理功能
        // Route::post('menus/active/{id}', [MenuController::class, 'active']);
        // Route::get('menus/sortup/{id}',[MenuController::class, 'sortup']);
        // Route::get('menus/sortdown/{id}',[MenuController::class, 'sortdown']);
        // Route::get('menus/submenu/{id}',[MenuController::class, 'submenu']);
        // Route::resource('menus', MenuController::class);

        //測試功能
        // Route::get('testing', [DashboardController::class, 'testing'])->name('testing');

        // 登出
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    });

});

// 給 Laravel fallback 的 route('login')
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
