<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImageSetting as ImageSettingDB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('DB_MIGRATE_IMAGE_SETTINGS')) {
            $data = [
                [
                    'name' => '預設',
                    'type' => 'default',
                    'width' => 1920,
                    'height' => 1080,
                ],
                [
                    'name' => 'Logo',
                    'type' => 'logo',
                    'width' => 600,
                    'height' => 400,
                ],
                [
                    'name' => '輪播',
                    'type' => 'banner',
                    'width' => null,
                    'height' => 500,
                ],
                [
                    'name' => '菜單',
                    'type' => 'foodmenu',
                    'width' => 1200,
                    'height' => null,
                ],
            ];
            for($i=0; $i<count($data); $i++){
                ImageSettingDB::Create($data[$i]);
            }
            echo "圖片設定建立完成\n";
        }
    }
}

