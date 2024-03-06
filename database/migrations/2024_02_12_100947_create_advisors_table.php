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
        Schema::create('advisors', function (Blueprint $table) {
            $table->id();
            $table->string('bio');
            $table->text('expertise');
            $table->text('Offere');
            $table->boolean('approved')->default(false);
            $table->foreignId('seeker_id')->references('id')->on('seekers');
            $table->timestamps();

            //$table->enum('approve', ['active', 'notActive'])->default('notactive');
            //$table->float('Session_Pricing', 8, 2)->nullable(); // تصحيح تعريف العمود Session_Pricing
            //$table->string('Certifications')->nullable();
            //$table->json('Availability_Session')->nullable();
            //$table->text('Professional_Summary')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisors');
    }
};
