<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Student Identifier
            $table->string('student_id')->unique(); // auto-generated student number
            $table->enum('student_type', ['new', 'old', 'continuing', 'transferee']);

            // Personal Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->string('gender');
            $table->string('nationality');
            $table->string('religion')->nullable();

            // Contact Information
            $table->string('region_code');
            // $table->string('address');
            $table->string('province_code')->nullable();
            $table->string('city_code')->nullable();
            $table->string('barangay_code')->nullable();
            $table->string('street')->nullable();
            $table->string('contact_no');
            $table->string('email');

            // Family Information
            $table->string('father_name');
            $table->string('father_occupation')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('mother_name');
            $table->string('mother_occupation')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();

            // Academic Background
            $table->enum('year_level', ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12']); // Grade levels
            $table->enum('strand', ['STEM', 'ABM', 'HUMSS', 'TVL', 'GAS'])->nullable(); // Academic strands
            $table->string('previous_school');
            $table->string('previous_school_address')->nullable();
            $table->string('lrn')->unique();
            $table->string('report_card')->nullable(); // file upload
            $table->string('form_137')->nullable();

            // Admission Requirements (uploads)
            $table->string('birth_certificate')->nullable();
            $table->string('good_moral')->nullable();
            $table->string('id_picture')->nullable();
            $table->string('medical_certificate')->nullable();
            // $table->string('exam_result')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }
};
