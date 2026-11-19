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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->json('selected_subjects')->nullable()->after('semester')->comment('Subject IDs that transferee student needs to take');
            $table->json('dropped_subjects')->nullable()->after('selected_subjects')->comment('Subject IDs that transferee student already took and wants to drop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['selected_subjects', 'dropped_subjects']);
        });
    }
};
