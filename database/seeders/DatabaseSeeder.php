<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(env('DB_SEED')){
            if(env('DB_SEED_SETTING')){
                $this->call(SettingSeeder::class);
            }else{
                echo "SettingSeeder 已被關閉\n";
            }
            if(env('DB_SEED_MENU')){
                $this->call(MainmenuSeeder::class);
            }else{
                echo "MainmenuSeeder 已被關閉\n";
            }
            if(env('DB_SEED_ADMIN')){
                $this->call(AdminSeeder::class);
            }else{
                echo "AdminSeeder 已被關閉\n";
            }
            if(env('DB_SEED_ARTICLE')){
                $this->call(ArticleSeeder::class);
            }else{
                echo "ArticleSeeder 已被關閉\n";
            }
            // if(env('DB_SEED_PRODUCT')){
            //     $this->call(ProductSeeder::class);
            // }else{
            //     echo "ProductSeeder 已被關閉\n";
            // }
            // if(env('DB_SEED_ORDER')){
            //     $this->call(OrderSeeder::class);
            // }else{
            //     echo "OrderSeeder 已被關閉\n";
            // }
            // if(env('DB_SEED_Purchase')){
            //     $this->call(PurchaseSeeder::class);
            // }else{
            //     echo "PurchaseSeeder 已被關閉\n";
            // }
        }else{
            echo "本功能已被關閉\n";
        }
    }
}
