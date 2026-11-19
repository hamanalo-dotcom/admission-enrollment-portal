<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('form_137')->nullable()->after('status');
            $table->string('form_138')->nullable()->after('form_137');
            $table->string('birth_certificate')->nullable()->after('form_138');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['form_137', 'form_138', 'birth_certificate']);
        });
    }
};


