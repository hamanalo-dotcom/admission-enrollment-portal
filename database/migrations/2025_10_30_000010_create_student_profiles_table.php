<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

            // Personal Information
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();

            // Address & Contact
            $table->string('region_code')->nullable();
            $table->string('province_code')->nullable();
            $table->string('city_code')->nullable();
            $table->string('barangay_code')->nullable();
            $table->string('street')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable();

            // Family
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();

            // Academic snapshot
            $table->string('year_level')->nullable();
            $table->string('strand')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('previous_school_address')->nullable();
            $table->string('lrn')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};


