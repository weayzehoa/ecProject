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
        if (env('DB_MIGRATE_TOTAL_DISCOUNTS')) {
            Schema::create('total_discounts', function (Blueprint $table) {
                $table->id();
                $table->integer('amount')->nullable()->default(0)->comment('總額');
                $table->integer('discount')->nullable()->default(0)->comment('折扣金額');
                $table->datetime('start_time')->nullable()->comment('折扣開始時間');
                $table->datetime('end_time')->nullable()->comment('折扣結束時間');
                $table->boolean('is_on')->default(0)->comment('啟用狀態');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (env('DB_MIGRATE_TOTAL_DISCOUNTS')) {
            Schema::dropIfExists('total_discounts');
        }
    }
};
