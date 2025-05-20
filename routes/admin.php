<?php

use App\Http\Controllers\Admin\{
    LoginController,
    DashboardController,
    CompanySettingController,
    SystemSettingController,
    AdminController,
    AdminLogController,
    UserController,
    ProductController,
    CategoryController,
    OrderController,
    PayMethodController,
    ShippingFeeController,
    AboutUsController,
    BannerController,
    NewsController,
    StoreController,
    FoodMenuController,
    CKEditorController,
    ImageSettingController,
    MainmenuController,
    SubmenuController,
};

Route::fallback(function () {
    return redirect()->route('admin.dashboard');
});

Route::name('admin.')->group(function () {
    Route::get('forgetPass', [LoginController::class, 'showForgetForm'])->name('forgetPass');
    Route::post('forgetPass', [LoginController::class, 'sendForgetMail'])->name('forgetPass.submit');
    Route::get('resetPass/{token}', [LoginController::class, 'showRestForm'])->name('resetPass');
    Route::post('resetPass', [LoginController::class, 'resetPass'])->name('resetPass.submit');

    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // 公司資料設定
        Route::middleware(['checkPermission:companySettings'])->group(function () {
            Route::get('companySettings', [CompanySettingController::class, 'index'])->name('companySettings.index');
            Route::match(['put', 'patch'], 'companySettings/{companySetting}', [CompanySettingController::class, 'update'])->name('companySettings.update')->middleware('checkPermission:companySettings,M');
        });

        // 系統設定
        Route::middleware(['checkPermission:systemSettings'])->group(function () {
            Route::get('systemSettings', [SystemSettingController::class, 'index'])->name('systemSettings.index');
            Route::match(['put', 'patch'], 'systemSettings/{systemSettings}', [SystemSettingController::class, 'update'])->name('systemSettings.update')->middleware('checkPermission:systemSettings,M');
        });

        // 管理員帳號
        Route::middleware(['checkPermission:admins'])->group(function () {
            Route::post('admins/active/{id}', [AdminController::class, 'active'])->name('admins.active')->middleware('checkPermission:admins,O');
            Route::get('admins', [AdminController::class, 'index'])->name('admins.index');
            Route::get('admins/create', [AdminController::class, 'create'])->name('admins.create')->middleware('checkPermission:admins,N');
            Route::post('admins', [AdminController::class, 'store'])->name('admins.store')->middleware('checkPermission:admins,N');
            Route::get('admins/{admin}', [AdminController::class, 'show'])->name('admins.show')->middleware('checkPermission:admins,M');
            Route::match(['put', 'patch'], 'admins/{admin}', [AdminController::class, 'update'])->name('admins.update')->middleware('checkPermission:admins,M');
            Route::delete('admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy')->middleware('checkPermission:admins,D');
        });

        // 管理者操作紀錄
        Route::middleware(['checkPermission:adminLogs'])->group(function () {
            Route::get('adminLogs', [AdminLogController::class, 'index'])->name('adminLogs.index');
        });

        // // 商品管理
        // Route::middleware(['checkPermission:products'])->group(function () {
        //     Route::resource('products', ProductController::class);
        // });

        // // 商品管理
        // Route::middleware(['checkPermission:users'])->group(function () {
        //     Route::resource('users', UserController::class);
        // });

        // // 商品分類
        // Route::middleware(['checkPermission:categories'])->group(function () {
        //     Route::resource('categories', CategoryController::class);
        // });

        // // 訂單管理
        // Route::middleware(['checkPermission:orders'])->group(function () {
        //     Route::resource('orders', OrderController::class);
        // });

        // // 付款方式設定
        // Route::middleware(['checkPermission:payMethods'])->group(function () {
        //     Route::resource('payMethods', PayMethodController::class);
        // });

        // 運費折扣設定
        Route::middleware(['checkPermission:shippingFees'])->group(function () {
            Route::post('shippingFees/active/{id}', [ShippingFeeController::class, 'active'])->name('shippingFees.active')->middleware('checkPermission:foodmenus,O');
            Route::get('shippingFees', [ShippingFeeController::class, 'index'])->name('shippingFees.index');
            Route::get('shippingFees/create', [ShippingFeeController::class, 'create'])->name('shippingFees.create')->middleware('checkPermission:foodmenus,N');
            Route::post('shippingFees', [ShippingFeeController::class, 'store'])->name('shippingFees.store')->middleware('checkPermission:foodmenus,N');
            Route::get('shippingFees/{shippingFees}', [ShippingFeeController::class, 'show'])->name('shippingFees.show')->middleware('checkPermission:foodmenus,M');
            Route::match(['put', 'patch'], 'shippingFees/{shippingFees}', [ShippingFeeController::class, 'update'])->name('shippingFees.update')->middleware('checkPermission:foodmenus,M');
            Route::delete('shippingFees/{shippingFees}', [ShippingFeeController::class, 'destroy'])->name('shippingFees.destroy')->middleware('checkPermission:foodmenus,D');
        });

        // // 關於我們
        // Route::middleware(['checkPermission:aboutUs'])->group(function () {
        //     Route::resource('aboutUs', AboutUsController::class);
        // });

        // 輪播管理
        Route::middleware(['checkPermission:banners'])->group(function () {
            Route::post('banners/delimg/{id}', [BannerController::class, 'delimg'])->name('banners.delimg');
            Route::post('banners/preview/{id}', [BannerController::class, 'preview'])->name('banners.preview');
            Route::post('banners/active/{id}', [BannerController::class, 'active'])->name('banners.active')->middleware('checkPermission:banners,O');
            Route::get('banners/sortup/{id}', [BannerController::class, 'sortup'])->name('banners.sortup')->middleware('checkPermission:banners,S');
            Route::get('banners/sortdown/{id}', [BannerController::class, 'sortdown'])->name('banners.sortdown')->middleware('checkPermission:banners,S');
            Route::get('banners', [BannerController::class, 'index'])->name('banners.index');
            Route::get('banners/create', [BannerController::class, 'create'])->name('banners.create')->middleware('checkPermission:banners,N');
            Route::post('banners', [BannerController::class, 'store'])->name('banners.store')->middleware('checkPermission:banners,N');
            Route::get('banners/{banner}', [BannerController::class, 'show'])->name('banners.show')->middleware('checkPermission:banners,M');
            Route::match(['put', 'patch'], 'banners/{banner}', [BannerController::class, 'update'])->name('banners.update')->middleware('checkPermission:banners,M');
            Route::delete('banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy')->middleware('checkPermission:banners,D');
        });

        // 最新消息
        Route::middleware(['checkPermission:news'])->group(function () {
            Route::post('news/preview/{id}', [NewsController::class, 'preview'])->name('news.preview');
            Route::post('news/active/{id}', [NewsController::class, 'active'])->name('news.active')->middleware('checkPermission:news,O');
            Route::get('news/sortup/{id}', [NewsController::class, 'sortup'])->name('news.sortup')->middleware('checkPermission:news,S');
            Route::get('news/sortdown/{id}', [NewsController::class, 'sortdown'])->name('news.sortdown')->middleware('checkPermission:news,S');
            Route::get('news', [NewsController::class, 'index'])->name('news.index');
            Route::get('news/create', [NewsController::class, 'create'])->name('news.create')->middleware('checkPermission:news,N');
            Route::post('news', [NewsController::class, 'store'])->name('news.store')->middleware('checkPermission:news,N');
            Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show')->middleware('checkPermission:news,M');
            Route::match(['put', 'patch'], 'news/{news}', [NewsController::class, 'update'])->name('news.update')->middleware('checkPermission:news,M');
            Route::delete('news/{news}', [NewsController::class, 'destroy'])->name('news.destroy')->middleware('checkPermission:news,D');
        });

        // 分店資訊
        Route::middleware(['checkPermission:stores'])->group(function () {
            Route::post('stores/delimg/{id}', [StoreController::class, 'delimg'])->name('stores.delimg');
            Route::post('stores/preview/{id}', [StoreController::class, 'preview'])->name('stores.preview');
            Route::post('stores/active/{id}', [StoreController::class, 'active'])->name('stores.active')->middleware('checkPermission:stores,O');
            Route::get('stores/sortup/{id}', [StoreController::class, 'sortup'])->name('stores.sortup')->middleware('checkPermission:stores,S');
            Route::get('stores/sortdown/{id}', [StoreController::class, 'sortdown'])->name('stores.sortdown')->middleware('checkPermission:stores,S');
            Route::get('stores', [StoreController::class, 'index'])->name('stores.index');
            Route::get('stores/create', [StoreController::class, 'create'])->name('stores.create')->middleware('checkPermission:stores,N');
            Route::post('stores', [StoreController::class, 'store'])->name('stores.store')->middleware('checkPermission:stores,N');
            Route::get('stores/{store}', [StoreController::class, 'show'])->name('stores.show')->middleware('checkPermission:stores,M');
            Route::match(['put', 'patch'], 'stores/{store}', [StoreController::class, 'update'])->name('stores.update')->middleware('checkPermission:stores,M');
            Route::delete('stores/{store}', [StoreController::class, 'destroy'])->name('stores.destroy')->middleware('checkPermission:stores,D');
        });

        // 菜單資訊
        Route::middleware(['checkPermission:foodmenus'])->group(function () {
            Route::post('foodmenus/delimg/{id}', [FoodMenuController::class, 'delimg'])->name('foodmenus.delimg');
            Route::post('foodmenus/preview/{id}', [FoodMenuController::class, 'preview'])->name('foodmenus.preview');
            Route::post('foodmenus/active/{id}', [FoodMenuController::class, 'active'])->name('foodmenus.active')->middleware('checkPermission:foodmenus,O');
            Route::get('foodmenus/sortup/{id}', [FoodMenuController::class, 'sortup'])->name('foodmenus.sortup')->middleware('checkPermission:foodmenus,S');
            Route::get('foodmenus/sortdown/{id}', [FoodMenuController::class, 'sortdown'])->name('foodmenus.sortdown')->middleware('checkPermission:foodmenus,S');
            Route::get('foodmenus', [FoodMenuController::class, 'index'])->name('foodmenus.index');
            Route::get('foodmenus/create', [FoodMenuController::class, 'create'])->name('foodmenus.create')->middleware('checkPermission:foodmenus,N');
            Route::post('foodmenus', [FoodMenuController::class, 'store'])->name('foodmenus.store')->middleware('checkPermission:foodmenus,N');
            Route::get('foodmenus/{foodmenu}', [FoodMenuController::class, 'show'])->name('foodmenus.show')->middleware('checkPermission:foodmenus,M');
            Route::match(['put', 'patch'], 'foodmenus/{foodmenu}', [FoodMenuController::class, 'update'])->name('foodmenus.update')->middleware('checkPermission:foodmenus,M');
            Route::delete('foodmenus/{foodmenu}', [FoodMenuController::class, 'destroy'])->name('foodmenus.destroy')->middleware('checkPermission:foodmenus,D');
        });

        // 圖片上傳設定
        Route::middleware(['checkPermission:imageSettings'])->group(function () {
            Route::resource('imageSettings', ImageSettingController::class);
        });

        // 選單功能設定
        Route::middleware(['checkPermission:mainmenus'])->group(function () {
            Route::post('mainmenus/open/{id}', [MainmenuController::class, 'open'])->name('mainmenus.open');
            Route::post('mainmenus/active/{id}', [MainmenuController::class, 'active'])->name('mainmenus.active');
            Route::get('mainmenus/sortup/{id}', [MainmenuController::class, 'sortup'])->name('mainmenus.sortup');
            Route::get('mainmenus/sortdown/{id}', [MainmenuController::class, 'sortdown'])->name('mainmenus.sortdown');
            Route::get('mainmenus/submenu/{id}',[MainmenuController::class, 'submenu'])->name('mainmenus.submenu');
            Route::resource('mainmenus', MainmenuController::class);
            Route::post('mainmenus/submenus/open/{id}', [SubmenuController::class, 'open'])->name('submenus.open');
            Route::post('mainmenus/submenus/active/{id}', [SubmenuController::class, 'active'])->name('submenus.active');
            Route::get('mainmenus/submenus/sortup/{id}', [SubmenuController::class, 'sortup'])->name('submenus.sortup');
            Route::get('mainmenus/submenus/sortdown/{id}', [SubmenuController::class, 'sortdown'])->name('submenus.sortdown');
            Route::resource('mainmenus/submenus', SubmenuController::class);
        });

        // 測試與登出
        Route::get('testing', [DashboardController::class, 'testing'])->name('testing');
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');

        // CKEditor Uploads
        Route::post('ckeditorUpload', [CKEditorController::class, 'upload'])->name('ckeditorUpload');
        Route::post('ckeditorDelete', [CKEditorController::class, 'delete'])->name('ckeditorDelete');
    });
});
Route::get('resetPass/{token}', [LoginController::class, 'showRestForm'])->name('password.reset');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
