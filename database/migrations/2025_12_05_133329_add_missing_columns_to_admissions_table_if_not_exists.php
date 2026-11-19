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
        // Add gwa column if it doesn't exist
        if (!Schema::hasColumn('admissions', 'gwa')) {
            Schema::table('admissions', function (Blueprint $table) {
                $table->decimal('gwa', 5, 2)->nullable()->after('year_level');
            });
        }
        
        // Add tvl_branch column if it doesn't exist
        if (!Schema::hasColumn('admissions', 'tvl_branch')) {
            Schema::table('admissions', function (Blueprint $table) {
                $table->string('tvl_branch')->nullable()->after('strand');
            });
        }
        
        // Add is_draft column if it doesn't exist
        if (!Schema::hasColumn('admissions', 'is_draft')) {
            Schema::table('admissions', function (Blueprint $table) {
                $table->boolean('is_draft')->default(false)->after('email');
            });
        }
        
        // Add rejection_remarks column if it doesn't exist
        if (!Schema::hasColumn('admissions', 'rejection_remarks')) {
            Schema::table('admissions', function (Blueprint $table) {
                $table->text('rejection_remarks')->nullable()->after('status');
            });
        }
        
        // Add file_review_status column if it doesn't exist
        if (!Schema::hasColumn('admissions', 'file_review_status')) {
            Schema::table('admissions', function (Blueprint $table) {
                $table->json('file_review_status')->nullable()->after('rejection_remarks');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            // Only drop columns if they exist (to avoid errors)
            if (Schema::hasColumn('admissions', 'gwa')) {
                $table->dropColumn('gwa');
            }
            if (Schema::hasColumn('admissions', 'tvl_branch')) {
                $table->dropColumn('tvl_branch');
            }
            if (Schema::hasColumn('admissions', 'is_draft')) {
                $table->dropColumn('is_draft');
            }
            if (Schema::hasColumn('admissions', 'rejection_remarks')) {
                $table->dropColumn('rejection_remarks');
            }
            if (Schema::hasColumn('admissions', 'file_review_status')) {
                $table->dropColumn('file_review_status');
            }
        });
    }
};
