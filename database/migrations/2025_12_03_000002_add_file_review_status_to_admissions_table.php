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
        Schema::table('admissions', function (Blueprint $table) {
            // JSON column to store per-file review statuses, e.g. {"report_card": "accepted", "form_137": "rejected"}
            $table->json('file_review_status')->nullable()->after('rejection_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn('file_review_status');
        });
    }
};


