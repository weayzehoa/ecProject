<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin as AdminDB;
use App\Models\Mainmenu as MainmenuDB;
use App\Models\Submenu as SubmenuDB;
use App\Models\CompanySetting as CompanySettingDB;
use App\Models\Store as StoreDB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('DB_MIGRATE_ADMINS')) {
            $staffPremission = $premission =null; $mpower = $staffPremissions = $premissions = [];
            $mainmenus = MainmenuDB::with('submenu')->get();
            foreach ($mainmenus as $mainmenu) {
                $staffPremissions[] = $mainmenu->code;
                $mpower = explode(',',$mainmenu->power_action);
                if(count($mpower) > 0){
                    for($i=0;$i<count($mpower);$i++){
                        $premissions[] = $mainmenu->code.$mpower[$i];
                    }
                }
                $staffPremissions[] = $premissions[] = $mainmenu->code;
                foreach ($mainmenu->submenu as $submenu) {
                    $staffPremissions[] = $submenu->code;
                    $spower = explode(',',$submenu->power_action);
                    if(count($spower) > 0){
                        for($i=0;$i<count($spower);$i++){
                            $premissions[] = $submenu->code.$spower[$i];
                        }
                    }
                    $premissions[] = $submenu->code;
                }
            }
            $premission = implode(',', $premissions);
            $staffPremission = implode(',', $staffPremissions);
            $admins = [
                [
                    'role' => 'develop',
                    'name' => '開發團隊',
                    'email' => 'develop@dgfactor.com.tw',
                    'tel' => '02-2953-0353',
                    'mobile' => '0928-589-779',
                    'password' => Hash::make('dg12853714'),
                    'permissions' => null,
                    'is_on' => 1,
                    'is_lock' => 0,
                ],
                [
                    'role' => 'admin',
                    'name' => '系統管理員',
                    'email' => 'admin@dgfactor.com.tw',
                    'tel' => '02-2953-0353',
                    'mobile' => '0928-589-779',
                    'password' => Hash::make('dg12853714'),
                    'permissions' => $premission,
                    'is_on' => 1,
                    'is_lock' => 0,
                ],
                [
                    'role' => 'staff',
                    'name' => 'Staff 員工',
                    'email' => 'staff@dgfactor.com.tw',
                    'tel' => '02-2953-0353',
                    'mobile' => '0928-589-779',
                    'password' => Hash::make('dg12853714'),
                    'permissions' => $staffPremission,
                    'is_on' => 1,
                    'is_lock' => 0,
                ]
            ];
            foreach ($admins as $admin) {
                AdminDB::updateOrCreate(
                    ['email' => $admin['email']], // 用 email 判斷是否存在
                    $admin
                );
            }
            echo "管理員建立完成\n";
        }

        if (env('DB_MIGRATE_COMPANY_SETTINGS')) {
            CompanySettingDB::create([
                'name' => '數位因子網路科技有限公司',
                'name_en' => '數位因子網路科技有限公司',
                'brand' => 'Digital Factory',
                'tax_num' => '12853714',
                'tel' => '02-22553653',
                'fax' => '02-22553431',
                'address' => '220新北市板橋區四川路一段23號9號',
                'address_en' => '220新北市板橋區四川路一段23號9號',
                'lon' => '25.00713236992322',
                'lat' => '121.45955676734683',
                'service_tel' => null,
                'service_email' => null,
                'service_time_start' => '早上 09:00',
                'service_time_end' => '下午 18:00',
                'website' => 'www.dgfactor.com',
                'url' => 'https://www.dgfactor.com',
                'fb_url' => 'https://facebook.com',
                'ig_url' => 'https://instagram.com',
                'line_id' => null,
                'line_qrcode' => null,
                'wechat_id' => null,
                'admin_id' => 2,
            ]);
            echo "COMPANY Setting 建立完成\n";
        }

        if (env('DB_MIGRATE_STORES')) {
            StoreDB::create([
                'name' => '台北店',
                'intro' => '台北店簡介',
                'description' => '台北店詳細介紹',
                'boss' => '陳威宏',
                'contact' => 'Roger Wu',
                'tax_num' => '12853714',
                'tel' => '02-22553653',
                'fax' => '02-22553431',
                'address' => '220新北市板橋區四川路一段23號9號',
                'email' => null,
                'img' => null,
                'lon' => '25.00713236992322',
                'lat' => '121.45955676734683',
                'service_time_start' => '早上 09:00',
                'service_time_end' => '下午 18:00',
                'url' => 'https://www.dgfactor.com',
                'fb_url' => 'https://facebook.com',
                'ig_url' => 'https://instagram.com',
                'line_id' => null,
                'line_qrcode' => null,
                'wechat_id' => null,
                'is_on' => 1,
                'is_preview' => 0,
                'sort' => 1,
            ]);
            echo "Store 分店建立完成\n";
        }
    }
}

