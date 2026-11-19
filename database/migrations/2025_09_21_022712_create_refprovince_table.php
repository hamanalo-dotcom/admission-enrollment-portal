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
        Schema::create('refprovince', function (Blueprint $table) {
            $table->string('provCode')->primary();
            $table->string('provDesc');
            $table->string('regCode');
            $table->string('psgcCode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refprovince');
    }
};
