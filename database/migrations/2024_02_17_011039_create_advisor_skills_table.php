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
        Schema::create('advisor_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advisor_id')->references('id')->on('advisors');
            $table->foreignId('skill_id')->references('id')->on('skills');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisor_skill');
    }
};
