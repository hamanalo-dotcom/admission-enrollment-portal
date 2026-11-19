<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop table if it exists (from failed migration)
        Schema::dropIfExists('teacher_section_assignments');
        
        Schema::create('teacher_section_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('grade_level'); // e.g., "Grade 7", "Grade 11"
            $table->string('section'); // e.g., "A", "B", "C"
            $table->string('strand')->nullable(); // For SHS: STEM, ABM, HUMSS, TVL, GAS
            $table->string('school_year'); // e.g., "2024-2025"
            $table->timestamps();
        });

        // Add unique constraint separately after table creation
        Schema::table('teacher_section_assignments', function (Blueprint $table) {
            $table->unique(['teacher_id', 'grade_level', 'section', 'school_year'], 'unique_teacher_assignment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_section_assignments');
    }
};

