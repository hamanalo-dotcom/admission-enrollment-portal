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
        // Ensure the table exists before altering it.
        if (!Schema::hasTable('announcements')) {
            return;
        }

        Schema::table('announcements', function (Blueprint $table) {
            if (!Schema::hasColumn('announcements', 'expiration_date')) {
                // Do not specify a column position; avoid referencing columns
                // that may not exist yet on fresh databases.
                $table->timestamp('expiration_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('announcements')) {
            return;
        }

        Schema::table('announcements', function (Blueprint $table) {
            if (Schema::hasColumn('announcements', 'expiration_date')) {
                $table->dropColumn('expiration_date');
            }
        });
    }
};


