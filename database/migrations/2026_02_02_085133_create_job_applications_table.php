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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_vacancies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('cv_path');
            $table->enum('status', ['pending', 'reviewing', 'shortlisted', 'rejected', 'accepted'])->default('pending');
            $table->text('cover_letter')->nullable();
            $table->text('notes')->nullable(); // Company notes
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();

            // Prevent duplicate applications
            $table->unique(['job_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
