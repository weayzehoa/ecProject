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
        if (env('DB_MIGRATE_ADMIN_LOGS')) {
            Schema::create('admin_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->string('action');
                $table->longText('description')->nullable()->comment('紀錄 JSON 字串，支援大型物件/陣列');
                $table->ipAddress('ip')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (env('DB_MIGRATE_ADMIN_LOGS')) {
            Schema::dropIfExists('admin_logs');
        }
    }
};
