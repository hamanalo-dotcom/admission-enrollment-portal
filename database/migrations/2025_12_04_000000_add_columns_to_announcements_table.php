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
        Schema::table('announcements', function (Blueprint $table) {
            if (!Schema::hasColumn('announcements', 'title')) {
                $table->string('title')->after('id');
            }
            if (!Schema::hasColumn('announcements', 'message')) {
                $table->text('message')->after('title');
            }
            if (!Schema::hasColumn('announcements', 'type')) {
                $table->enum('type', ['info', 'warning', 'success', 'danger'])->default('info')->after('message');
            }
            if (!Schema::hasColumn('announcements', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('type');
            }
            if (!Schema::hasColumn('announcements', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['title', 'message', 'type', 'is_active', 'published_at']);
        });
    }
};

