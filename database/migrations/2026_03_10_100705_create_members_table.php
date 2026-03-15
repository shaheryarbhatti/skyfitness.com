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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            // Personal identification
            $table->string('full_name', 100);       // Full name
            $table->string('nik', 16)->unique()->nullable();  
            // NIK = 16-digit Indonesian Citizen ID (unique, can be null if not required at signup)

            // Birth information
            $table->string('place_of_birth', 100)->nullable();
            $table->date('date_of_birth')->nullable();

            // Basic profile
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('blood_type', 5)->nullable();        // e.g. A+, O-, AB, etc.
            $table->string('religion', 50)->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable()->default('single');

            // Professional & nationality
            $table->string('occupation', 100)->nullable();      // renamed 'Work' → occupation
            $table->string('citizenship', 100)->nullable()->default('Indonesian');

            // Contact information
            $table->string('email', 120)->unique()->nullable();
            $table->string('phone', 20)->unique()->nullable();  // mobile phone number

            // Location
            $table->text('address')->nullable();

            // Profile picture
            $table->string('photo')->nullable();                // path to stored photo e.g. "members/abc123.jpg"

            // Gym-specific status
            $table->boolean('status')->default(1);              // 1 = active, 0 = inactive/suspended
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
