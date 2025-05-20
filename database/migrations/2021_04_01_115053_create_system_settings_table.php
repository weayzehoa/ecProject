<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('DB_MIGRATE_SYSTEM_SETTINGS')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('shippingFees')->nullable()->comment('運費模組啟用狀態 0:停用 1:啟用');
                $table->boolean('totalDiscounts')->nullable()->comment('滿額折扣模組啟用狀態 0:停用 1:啟用');
                $table->boolean('productPromos')->nullable()->comment('商品限時優惠模組啟用狀態 0:停用 1:啟用');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env('DB_MIGRATE_SYSTEM_SETTINGS')) {
            Schema::dropIfExists('system_settings');
        }
    }
}
