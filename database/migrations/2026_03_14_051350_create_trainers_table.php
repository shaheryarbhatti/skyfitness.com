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
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            // Full name (nullable if not required)
            $table->string('full_name');

            // E-mail (unique for login)
            $table->string('email')->unique();

            // Phone number
            $table->string('phone_number')->nullable();

            /**
             * Trainer Type (from dropdown)
             * Standardizing "Trainer Type" to "trainer_type" (underscore).
             * Examples in DB: 'master', 'senior', 'junior'
             */
            $table->string('trainer_type')->nullable();

            /**
             * Gender (from dropdown)
             * Standardizing to "gender".
             * Examples in DB: 'man', 'woman', 'other'
             */
            $table->string('gender')->nullable();

            /**
             * Status (from dropdown)
             * Set default to "active" to match the default in the image.
             * Examples in DB: 'active', 'inactive', 'pending'
             */
            $table->string('status')->default('active');

            // Specialization (textarea or text)
            $table->text('specialization')->nullable();

            // Photo path (for file upload/camera)
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
