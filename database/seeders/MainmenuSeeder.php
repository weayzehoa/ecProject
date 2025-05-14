<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mainmenu as MainmenuDB;
use App\Models\Submenu as SubmenuDB;
use Illuminate\Support\Facades\DB;

class MainmenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mainmenu = [
            ['sort' => 1, 'is_on' => 1, 'url_type' => 0, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-cogs"></i>', 'name' => '系統管理', 'url' => '', 'func_code' => ''],
            ['sort' => 2, 'is_on' => 0, 'url_type' => 0, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-users"></i>', 'name' => '會員管理', 'url' => '', 'func_code' => 'users'],
            ['sort' => 3, 'is_on' => 1, 'url_type' => 0, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-store"></i>', 'name' => '產品管理', 'url' => '', 'func_code' => 'products'],
            ['sort' => 4, 'is_on' => 1, 'url_type' => 0, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-clipboard-list"></i>', 'name' => '訂單管理', 'url' => '', 'func_code' => 'orders'],
            ['sort' => 5, 'is_on' => 1, 'url_type' => 0, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-ad"></i>', 'name' => '網站管理', 'url' => '', 'func_code' => ''],
            ['sort' => 6, 'is_on' => 1, 'url_type' => 0, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop', 'icon' => '<i class="nav-icon fas fa-tools"></i>', 'name' => '開發團隊專用', 'url' => '', 'func_code' => ''],
        ];
        $submenu = [
            [ //系統管理
                ['sort' => 1, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'M', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-money-check"></i>', 'name' => '公司資料設定', 'url' => 'companySettings', 'func_code' => ''],
                ['sort' => 2, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'M', 'allow_roles' => 'develop,admin', 'icon' => '<i class="nav-icon fas fa-tools"></i>', 'name' => '系統參數設定', 'url' => 'systemSettings', 'func_code' => ''],
                ['sort' => 3, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,D,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-users-cog""></i>', 'name' => '管理員帳號', 'url' => 'admins', 'func_code' => ''],
                ['sort' => 4, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop,admin', 'icon' => '<i class="nav-icon fas fa-clipboard-list"></i>', 'name' => '管理者操作紀錄', 'url' => 'adminLogs', 'func_code' => ''],
                ['sort' => 5, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon far fa-circle"></i>', 'name' => '預留功能', 'url' => '', 'func_code' => ''],
            ],
            [ //會員管理
                ['sort' => 1, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,D,M,O,EX', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-users-cog"></i>', 'name' => '會員列表', 'url' => 'users', 'func_code' => 'users'],
                ['sort' => 2, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,D,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon far fa-envelope"></i>', 'name' => '聯絡我們', 'url' => 'contactUs', 'func_code' => ''],
                ['sort' => 3, 'is_on' => 0, 'url_type' => 2, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-shopping-cart"></i>', 'name' => '會員購物車', 'url' => '', 'func_code' => 'users'],
                ['sort' => 4, 'is_on' => 0, 'url_type' => 2, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon far fa-circle"></i>', 'name' => '預留功能', 'url' => '', 'func_code' => ''],
                ['sort' => 5, 'is_on' => 0, 'url_type' => 2, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon far fa-circle"></i>', 'name' => '預留功能', 'url' => '', 'func_code' => ''],
            ],
            [ //產品管理
                ['sort' => 1, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,D,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-list-ol"></i>', 'name' => '商品管理', 'url' => 'products',  'func_code' => 'products'],
                ['sort' => 2, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fab fa-buromobelexperte"></i>', 'name' => '商品分類設定', 'url' => 'categories',  'func_code' => 'products'],
                ['sort' => 3, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,D,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon far fa-circle"></i>', 'name' => '預留功能', 'url' => '',  'func_code' => 'products'],
                ['sort' => 4, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,D,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon far fa-circle"></i>', 'name' => '預留功能', 'url' => '',  'func_code' => 'products'],
                ['sort' => 5, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,D,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon far fa-circle"></i>', 'name' => '預留功能', 'url' => '',  'func_code' => 'products'],
            ],
            [ //訂單管理
                ['sort' => 1, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'M', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-file-invoice-dollar"></i>', 'name' => '訂單管理', 'url' => 'orders',  'func_code' => 'orders'],
                ['sort' => 2, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-credit-card"></i>', 'name' => '付款方式設定', 'url' => 'payMethods',  'func_code' => 'orders'],
                ['sort' => 3, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-truck"></i>', 'name' => '運費折扣設定', 'url' => 'shippingFees',  'func_code' => 'orders'],
                ['sort' => 4, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon far fa-circle"></i>', 'name' => '預留功能', 'url' => 'shippingFees',  'func_code' => 'orders'],
                ['sort' => 5, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon far fa-circle"></i>', 'name' => '預留功能', 'url' => 'shippingFees',  'func_code' => 'orders'],
            ],
            [ //行銷策展
                ['sort' => 1, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,O,S', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-ad"></i>', 'name' => '首頁策展', 'url' => 'curations',  'func_code' => ''],
                ['sort' => 2, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-bullhorn"></i>', 'name' => '推薦碼設定', 'url' => '',  'func_code' => 'promotions'],
                ['sort' => 3, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-bullhorn"></i>', 'name' => '優惠活動設定', 'url' => '',  'func_code' => 'promotions'],
                ['sort' => 4, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,O', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-bullhorn"></i>', 'name' => '促銷代碼設定', 'url' => '',  'func_code' => 'promotions'],
                ['sort' => 5, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,D,O,S', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-file-alt"></i>', 'name' => '關於我們', 'url' => 'aboutUs',  'func_code' => 'aboutUs'],
                ['sort' => 6, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,D,O,S', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon fas fa-images"></i>', 'name' => '輪播管理', 'url' => 'banners',  'func_code' => 'banners'],
                ['sort' => 7, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,D,O,S', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon far fa-newspaper"></i>', 'name' => '最新消息', 'url' => 'news',  'func_code' => 'news'],
                ['sort' => 8, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,D,O,S', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon  fab fa-elementor"></i>', 'name' => '菜單資訊', 'url' => 'foodmenus',  'func_code' => 'foodmenus'],
                ['sort' => 9, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,D,O,S', 'allow_roles' => 'develop,admin,staff', 'icon' => '<i class="nav-icon  fas fa-store-alt"></i>', 'name' => '分店資訊', 'url' => 'stores',  'func_code' => 'stores'],
                ['sort' => 10, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => 'N,M,O', 'allow_roles' => 'develop', 'icon' => '<i class="nav-icon  far fa-circle"></i>', 'name' => '預留功能', 'url' => '',  'func_code' => ''],
            ],
            [ //開發團隊專用
                ['sort' => 5, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop', 'icon' => '<i class="nav-icon fas fa-tools"></i>', 'name' => '後台開發測試', 'url' => 'testing',  'func_code' => ''],
                ['sort' => 1, 'is_on' => 1, 'url_type' => 1, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop', 'icon' => '<i class="nav-icon fas fa-tools"></i>', 'name' => '圖片上傳設定', 'url' => 'imageSettings',  'func_code' => ''],
                ['sort' => 2, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop', 'icon' => '<i class="nav-icon fas far fa-circle"></i>', 'name' => '功能管理', 'url' => '',  'func_code' => ''],
                ['sort' => 3, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop', 'icon' => '<i class="nav-icon fas far fa-circle"></i>', 'name' => '預留功能', 'url' => '',  'func_code' => ''],
                ['sort' => 4, 'is_on' => 0, 'url_type' => 1, 'open_window' => 0, 'power_action' => '', 'allow_roles' => 'develop', 'icon' => '<i class="nav-icon fas far fa-circle"></i>', 'name' => '預留功能', 'url' => '',  'func_code' => ''],
            ]
        ];

        if (env('DB_MIGRATE_MAINMENUS')) {
            //清空資料表
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            MainmenuDB::truncate();
            SubmenuDB::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $s1 = $s2 = $s3 = 0;
            for ($i=0;$i<count($mainmenu);$i++) {
                MainmenuDB::create([
                    'code' => 'M'.($i+1).'S0',
                    'name' => $mainmenu[$i]['name'],
                    'icon' => $mainmenu[$i]['icon'],
                    'power_action' => $mainmenu[$i]['power_action'],
                    'allow_roles' => $mainmenu[$i]['allow_roles'],
                    'url' => $mainmenu[$i]['url'],
                    'url_type' => $mainmenu[$i]['url_type'],
                    'open_window' => $mainmenu[$i]['open_window'],
                    'is_on' => $mainmenu[$i]['is_on'],
                    'sort' => $mainmenu[$i]['sort'],
                    'func_code' => $mainmenu[$i]['func_code'],
                ]);
                if ($mainmenu[$i]['url_type']==0) {
                    if(!empty($submenu[$i])){
                        for ($j=0;$j<count($submenu[$i]);$j++) {
                            SubmenuDB::create([
                                'mainmenu_id' => $i+1,
                                'code' => 'M'.($i+1).'S'.($j+1),
                                'name' => $submenu[$i][$j]['name'],
                                'icon' => $submenu[$i][$j]['icon'],
                                'power_action' => $submenu[$i][$j]['power_action'],
                                'url' => $submenu[$i][$j]['url'],
                                'url_type' => $submenu[$i][$j]['url_type'],
                                'open_window' => $submenu[$i][$j]['open_window'],
                                'is_on' => $submenu[$i][$j]['is_on'],
                                'sort' => $submenu[$i][$j]['sort'],
                                'func_code' => $submenu[$i][$j]['func_code'],
                            ]);
                        }
                    }
                }
            }
            echo "後台選單建立完成\n";
        }

        $PowerActions = [
            ['name' => '新增', 'code' => 'N'],
            ['name' => '刪除', 'code' => 'D'],
            ['name' => '修改', 'code' => 'M'],
            ['name' => '啟用', 'code' => 'O'],
            ['name' => '排序', 'code' => 'S'],
            ['name' => '匯入', 'code' => 'IM'],
            ['name' => '匯出', 'code' => 'EX'],
            ['name' => '下載', 'code' => 'DL'],
            ['name' => '註記', 'code' => 'MK'],
            ['name' => '列印', 'code' => 'PR'],
            ['name' => '發送簡訊', 'code' => 'SMS'],
            ['name' => '發送信件', 'code' => 'SEM'],
        ];

        $funcCodes = [
            ['name' => '會員功能', 'code' => 'users', 'is_on' => 1],
            ['name' => '商品功能', 'code' => 'products', 'is_on' => 1],
            ['name' => '訂單功能', 'code' => 'orders', 'is_on' => 1],
            ['name' => '輪播功能', 'code' => 'banners', 'is_on' => 1],
            ['name' => '關於我們', 'code' => 'aboutUs', 'is_on' => 1],
            ['name' => '促銷功能', 'code' => 'promotions', 'is_on' => 1],
            ['name' => '最新消息', 'code' => 'news', 'is_on' => 1],
            ['name' => '分店資訊', 'code' => 'stores', 'is_on' => 1],
            ['name' => '菜單資訊', 'code' => 'foodmenus', 'is_on' => 1],
        ];
    }
}
