<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Article as ArticleDB;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('DB_MIGRATE_ARTICLES')) {

            $data = [
                [
                    'type' => 'news',
                    'title' => '最新消息測試一',
                    'description' => '最新消息測試一描述資料',
                    'content' => '最新消息測試一內容資料',
                    'url' => null,
                    'img' => null,
                    'file' => null,
                    'start_time' => null,
                    'end_time' => null,
                    'is_on' => 1,
                    'is_preview' => 0,
                    'sort' => 1,
                    'admin_id' => null,
                ],
                [
                    'type' => 'news',
                    'title' => '最新消息測試二',
                    'description' => '最新消息測試二描述資料',
                    'content' => '最新消息測試二內容資料',
                    'url' => null,
                    'img' => null,
                    'file' => null,
                    'start_time' => null,
                    'end_time' => null,
                    'is_on' => 1,
                    'is_preview' => 0,
                    'sort' => 2,
                    'admin_id' => null,
                ],
                [
                    'type' => 'news',
                    'title' => '最新消息測試三',
                    'description' => '最新消息測試三描述資料',
                    'content' => '最新消息測試三內容資料',
                    'url' => null,
                    'img' => null,
                    'file' => null,
                    'start_time' => null,
                    'end_time' => null,
                    'is_on' => 1,
                    'is_preview' => 0,
                    'sort' => 3,
                    'admin_id' => null,
                ],
                [
                    'type' => 'foodmenu',
                    'title' => '食物菜單',
                    'description' => '食物菜單描述資料',
                    'content' => '食物菜單內容資料',
                    'url' => null,
                    'img' => 'foodmenu.jpg',
                    'file' => null,
                    'start_time' => null,
                    'end_time' => null,
                    'is_on' => 1,
                    'is_preview' => 0,
                    'sort' => 1,
                    'admin_id' => null,
                ],
                [
                    'type' => 'foodmenu',
                    'title' => '飲料菜單',
                    'description' => '飲料菜單描述資料',
                    'content' => '飲料菜單內容資料',
                    'url' => null,
                    'img' => 'drinkmenu.jpg',
                    'file' => null,
                    'start_time' => null,
                    'end_time' => null,
                    'is_on' => 1,
                    'is_preview' => 0,
                    'sort' => 2,
                    'admin_id' => null,
                ],
                [
                    'type' => 'banner',
                    'title' => '輪播測試一',
                    'description' => '輪播測試一描述資料',
                    'content' => '輪播測試一內容資料',
                    'url' => null,
                    'img' => 'banner1.jpg',
                    'file' => null,
                    'start_time' => null,
                    'end_time' => null,
                    'is_on' => 1,
                    'is_preview' => 0,
                    'sort' => 1,
                    'admin_id' => null,
                ],
                [
                    'type' => 'banner',
                    'title' => '輪播測試二',
                    'description' => '輪播測試二描述資料',
                    'content' => '輪播測試二內容資料',
                    'url' => null,
                    'img' => 'banner2.jpg',
                    'file' => null,
                    'start_time' => null,
                    'end_time' => null,
                    'is_on' => 1,
                    'is_preview' => 0,
                    'sort' => 2,
                    'admin_id' => null,
                ],
                [
                    'type' => 'banner',
                    'title' => '輪播測試三',
                    'description' => '輪播測試三描述資料',
                    'content' => '輪播測試三內容資料',
                    'url' => null,
                    'img' => 'banner3.jpg',
                    'file' => null,
                    'start_time' => null,
                    'end_time' => null,
                    'is_on' => 1,
                    'is_preview' => 0,
                    'sort' => 3,
                    'admin_id' => null,
                ],
            ];
            for($i=0; $i<count($data); $i++){
                ArticleDB::Create($data[$i]);
            }

            echo "文章內容建立完成\n";
        }
    }
}

