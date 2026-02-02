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
        Schema::dropIfExists('ads');

        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->string('url')->nullable();
            $table->enum('placement', ['header', 'sidebar', 'footer', 'homepage'])->default('homepage');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('clicks')->default(0);
            $table->integer('impressions')->default(0);
            $table->timestamps();

            // Indexes for performance
            $table->index(['placement', 'is_active', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
