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
        if (env('DB_MIGRATE_IMAGE_SETTINGS')) {
            Schema::create('image_settings', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('名稱');
                $table->string('type')->comment('類別');
                $table->integer('width')->nullable()->comment('寬度限制');
                $table->integer('height')->nullable()->comment('高度限制');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (env('DB_MIGRATE_IMAGE_SETTINGS')) {
            Schema::dropIfExists('image_settings');
        }
    }
};
