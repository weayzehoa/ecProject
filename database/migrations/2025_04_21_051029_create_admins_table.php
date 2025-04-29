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
        if (env('DB_MIGRATE_ADMINS')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id()->comment('主鍵 ID');
                $table->string('role')->comment('腳色');
                $table->string('name')->comment('名稱');
                $table->string('email')->unique()->comment('Email 帳號');
                $table->string('tel')->nullable()->comment('連絡電話');
                $table->string('mobile')->nullable()->comment('手機號碼');
                $table->string('password')->comment('加密密碼');
                $table->text('permissions')->nullable()->comment('權限');
                $table->string('verify_mode', 5)->nullable()->comment('驗證方式 email, sms, 2fa');
                $table->string('2fa_secret')->nullable()->comment('2fa 密鑰');
                $table->boolean('is_on')->default(1)->comment('啟用狀態');
                $table->boolean('is_lock')->default(0)->comment('鎖定狀態');
                $table->string('otp_code', 6)->nullable()->comment('sms 驗證碼');
                $table->dateTime('otp_time')->nullable()->comment('驗證碼到期時間');
                $table->dateTime('off_time')->nullable()->comment('停用時間');
                $table->dateTime('last_login_time')->nullable()->comment('最後一次登入時間');
                $table->string('last_login_ip')->nullable()->comment('最後一次登入IP');
                $table->rememberToken();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (env('DB_MIGRATE_ADMINS')) {
            Schema::dropIfExists('admins');
        }
    }
};
