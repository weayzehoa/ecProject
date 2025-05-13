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
        if (env('DB_MIGRATE_STORES')) {
            Schema::create('stores', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('分店名稱');
                $table->text('intro')->nullable()->comment('簡介');
                $table->longText('description')->nullable()->comment('詳細介紹');
                $table->string('boss')->nullable()->comment('負責人');
                $table->string('contact')->nullable()->comment('聯絡人');
                $table->string('tax_num')->nullable()->comment('統編');
                $table->string('tel')->nullable()->comment('電話');
                $table->string('fax')->nullable()->comment('傳真');
                $table->string('address')->nullable()->comment('地址');
                $table->string('email')->nullable()->comment('電子郵件');
                $table->string('img')->nullable()->comment('圖片');
                $table->string('lon')->nullable()->comment('經度座標');
                $table->string('lat')->nullable()->comment('緯度座標');
                $table->string('service_time_start')->nullable()->comment('營業開始時間');
                $table->string('service_time_end')->nullable()->comment('營業結束時間');
                $table->string('url')->nullable()->comment('網站連結');
                $table->string('fb_url')->nullable()->comment('FB粉絲頁連結');
                $table->string('ig_url')->nullable()->comment('Instagram粉絲頁連結');
                $table->string('line_id')->nullable()->comment('line號碼');
                $table->string('line_qrcode')->nullable()->comment('line QRcode');
                $table->string('wechat_id')->nullable()->comment('wechat號碼');
                $table->boolean('is_on')->default(1)->comment('啟用狀態');
                $table->boolean('is_preview')->default(0)->comment('預覽狀態');
                $table->float('sort', 11, 1)->default(999999)->comment('排序');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (env('DB_MIGRATE_STORES')) {
            Schema::dropIfExists('stores');
        }
    }
};
