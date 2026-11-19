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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade'); // Student user
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // Teacher user
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->string('grade_level'); // e.g., "Grade 7", "Grade 11"
            $table->string('section'); // e.g., "HOPE", "ZAMORA"
            $table->string('strand')->nullable(); // For SHS
            $table->string('school_year'); // e.g., "2024-2025"
            $table->string('grading_period'); // e.g., "First Quarter", "Second Quarter", "Midterm", "Final"
            $table->decimal('grade_value', 5, 2); // e.g., 85.50, 92.00 (max 5 digits, 2 decimal places)
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Unique constraint: one grade per student, subject, grading period, school year
            $table->unique(['student_id', 'subject_id', 'grading_period', 'school_year'], 'unique_student_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
