<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Guard against running on a fresh database where the table
        // has not been created yet (created later in 2025_12_03_* migration).
        if (!Schema::hasTable('teacher_section_assignments')) {
            return;
        }

        Schema::table('teacher_section_assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_section_assignments', 'tvl_branch')) {
                $table->enum('tvl_branch', ['CSS', 'EIM'])->nullable()->after('strand');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teacher_section_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_section_assignments', 'tvl_branch')) {
                $table->dropColumn('tvl_branch');
            }
        });
    }
};


