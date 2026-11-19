<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            if (!Schema::hasColumn('admissions', 'tvl_branch')) {
                $table->enum('tvl_branch', ['CSS', 'EIM'])->nullable()->after('strand');
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'tvl_branch')) {
                $table->enum('tvl_branch', ['CSS', 'EIM'])->nullable()->after('strand');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            if (Schema::hasColumn('admissions', 'tvl_branch')) {
                $table->dropColumn('tvl_branch');
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'tvl_branch')) {
                $table->dropColumn('tvl_branch');
            }
        });
    }
};


