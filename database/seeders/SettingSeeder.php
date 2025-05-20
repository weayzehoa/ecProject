<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImageSetting as ImageSettingDB;
use App\Models\SystemSetting as SystemSettingDB;
use App\Models\ShippingFee as ShippingFeeDB;
use App\Models\TotalDiscount as TotalDiscountDB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('DB_MIGRATE_SYSTEM_SETTINGS')) {
            $data = [
                    'shippingFees' => 1,
                    'productPromos' => 0,
                    'TotalDiscounts' => 0,
                ];
            SystemSettingDB::Create($data);
            echo "系統設定建立完成\n";
        }

        if (env('DB_MIGRATE_IMAGE_SETTINGS')) {
            $data = [
                ['name' => '預設', 'type' => 'default', 'width' => 1920, 'height' => null, 'small_pic' => 1 ],
                ['name' => 'CKEditor', 'type' => 'ckeditor', 'width' => 1440, 'height' => null, 'small_pic' => 0 ],
                ['name' => '商品', 'type' => 'product', 'width' => 1600, 'height' => null, 'small_pic' => 0 ],
                ['name' => '分店', 'type' => 'store', 'width' => 1600, 'height' => null, 'small_pic' => 0],
                ['name' => 'Logo', 'type' => 'logo', 'width' => 500, 'height' => 500, 'small_pic' => 0 ],
                ['name' => '輪播', 'type' => 'banner', 'width' => null, 'height' => 500, 'small_pic' => 0 ],
                ['name' => '菜單', 'type' => 'foodmenu', 'width' => 1200, 'height' => null, 'small_pic' => 1 ],
            ];
            for($i=0; $i<count($data); $i++){
                ImageSettingDB::Create($data[$i]);
            }
            echo "圖片設定建立完成\n";
        }

        if (env('DB_MIGRATE_SHIPPING_FEES')) {
            $data = [
                ['name' => '常溫', 'type' => 'normal', 'fee' => 200, 'free' => 2000, 'discount' => 80, 'is_on' => 1],
                ['name' => '冷藏', 'type' => 'refrigeration', 'fee' => 250, 'free' => 2500, 'discount' => 50, 'is_on' => 1],
                ['name' => '冷凍', 'type' => 'freezing', 'fee' => 300, 'free' => 3000, 'discount' => 30, 'is_on' => 0 ],
            ];
            for($i=0; $i<count($data); $i++){
                ShippingFeeDB::Create($data[$i]);
            }
            echo "運費設定建立完成\n";
        }

        if (env('DB_MIGRATE_TOTAL_DISCOUNTS')) {
            $data = [
                ['amount' => '5000', 'discount' => 300, 'is_on' => 1],
                ['amount' => '10000', 'discount' => 1000, 'is_on' => 0],
            ];
            for($i=0; $i<count($data); $i++){
                TotalDiscountDB::Create($data[$i]);
            }
            echo "運費設定建立完成\n";
        }
    }
}

