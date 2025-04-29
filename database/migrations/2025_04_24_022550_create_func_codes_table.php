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
        if (env('DB_MIGRATE_FUNC_CODES')) {
            Schema::create('func_codes', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('名稱');
                $table->string('code')->comment('代碼');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (env('DB_MIGRATE_FUNC_CODES')) {
            Schema::dropIfExists('func_codes');
        }
    }
};
