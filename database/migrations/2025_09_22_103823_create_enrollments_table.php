<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            // Relations to admissions and users
            $table->foreignId('admission_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Enrollment Information
            $table->string('grade_level'); // Grade 7, 11, etc.
            $table->string('strand')->nullable(); // SHS strand/track
            $table->string('section')->nullable(); // Assigned by registrar
            $table->string('school_year'); // e.g., 2025-2026
            $table->string('semester')->nullable(); // for SHS only

            // Parent/Guardian Update
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();

            // Status
            $table->enum('status', ['pending', 'enrolled', 'cancelled', 'completed'])->default('pending');

            $table->timestamps();
        });
    }





    // $table->foreignId('student_ref')->nullable()->constrained('students')->onDelete('cascade');
    // $table->string('student_id')->nullable(); // display ID (e.g., 2025-0001)

    // // Student details
    // $table->string('full_name');
    // $table->string('grade_level');
    // $table->string('strand_track')->nullable();
    // $table->string('section')->nullable();
    // $table->string('school_year');
    // $table->string('semester')->nullable();

    // // Parent/Guardian
    // $table->string('father_name')->nullable();
    // $table->string('father_contact')->nullable();
    // $table->string('mother_name')->nullable();
    // $table->string('mother_contact')->nullable();
    // $table->string('guardian_name')->nullable();
    // $table->string('guardian_contact')->nullable();

    // // Academic records
    // $table->string('previous_grade_level')->nullable();
    // $table->decimal('general_average', 5, 2)->nullable();
    // $table->string('clearance')->nullable(); // file upload for old students
    // $table->string('lrn')->nullable();

    // // Requirements
    // $table->string('report_card')->nullable();

    // // Status
    // $table->enum('status', ['pending', 'enrolled', 'cancelled', 'completed', 'dropped'])
    //     ->default('pending');

    //             $table->timestamps();
    //         });
    //     }
    // };

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
