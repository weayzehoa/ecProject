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
        if (env('DB_MIGRATE_SHIPPING_FEES')) {
            Schema::create('shipping_fees', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('名稱');
                $table->string('type')->comment('類型');
                $table->integer('fee')->comment('費用');
                $table->integer('free')->nullable()->comment('免運門檻');
                $table->integer('discount')->nullable()->comment('折扣');
                $table->datetime('start_time')->nullable()->comment('折扣開始時間');
                $table->datetime('end_time')->nullable()->comment('折扣結束時間');
                $table->boolean('is_on')->default(1)->comment('啟用狀態');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (env('DB_MIGRATE_SHIPPING_FEES')) {
            Schema::dropIfExists('shipping_fees');
        }
    }
};
