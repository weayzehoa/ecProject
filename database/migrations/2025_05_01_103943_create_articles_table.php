<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (env('DB_MIGRATE_ARTICLES')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('type')->comment('文章類型');
                $table->string('title')->comment('中文標題');
                $table->string('description')->nullable()->comment('描述');
                $table->longText('content')->nullable()->comment('文章內容');
                $table->string('url')->nullable()->comment('連結');
                $table->string('img')->nullable()->comment('圖片');
                $table->string('file')->nullable()->comment('檔案');
                $table->datetime('start_time')->nullable()->comment('開始時間');
                $table->datetime('end_time')->nullable()->comment('結束時間');
                $table->boolean('is_on')->default(1)->comment('啟用狀態');
                $table->boolean('is_preview')->default(0)->comment('預覽狀態');
                $table->float('sort', 11, 1)->default(999999)->comment('排序');
                $table->integer('admin_id')->nullable()->comment('管理員 ID');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (env('DB_MIGRATE_ARTICLES')) {
            Schema::dropIfExists('articles');
        }
    }
};
