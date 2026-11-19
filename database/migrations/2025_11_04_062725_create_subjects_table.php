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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Mathematics", "English", "Science"
            $table->string('code')->unique()->nullable(); // e.g., "MATH", "ENG", "SCI"
            $table->string('grade_level')->nullable(); // e.g., "Grade 7", "Grade 11" or null for all
            $table->string('strand')->nullable(); // For SHS: "GAS", "CSS", "EIM" or null for all
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
