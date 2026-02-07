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
        Schema::table('tests', function (Blueprint $table) {
            // Видаляємо assigned_users, якщо він існує
            if (Schema::hasColumn('tests', 'assigned_users')) {
                $table->dropColumn('assigned_users');
            }

            // Додаємо JSON поле для рівнів ризику
            $table->json('risk_levels')->nullable()->after('questions_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('risk_levels');
            $table->json('assigned_users')->nullable();
        });
    }
};

