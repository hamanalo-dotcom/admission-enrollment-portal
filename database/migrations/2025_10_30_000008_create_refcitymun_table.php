<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refcitymun', function (Blueprint $table) {
            $table->string('psgcCode')->primary();
            $table->string('citymunDesc');
            $table->string('citymunCode')->unique();
            $table->string('provCode');
            $table->index('provCode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refcitymun');
    }
};


