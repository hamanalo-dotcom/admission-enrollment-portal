<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refbrgy', function (Blueprint $table) {
            $table->string('brgyCode')->primary();
            $table->string('brgyDesc');
            $table->string('citymunCode');
            $table->index('citymunCode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refbrgy');
    }
};


