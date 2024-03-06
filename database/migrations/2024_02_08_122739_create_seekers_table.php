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
        if (!Schema::hasTable('seekers')) {
            Schema::create('seekers', function (Blueprint $table) {
                $table->id();
                $table->char('uuid', 36)->nullable();
                $table->string('name');
                $table->string('email')->unique();
                $table->date('date_birth')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->string('provider_id')->nullable();
                $table->string('provider')->nullable();
                $table->enum('role', ['advisor', 'seeker'])->default('seeker'); //
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seekers');
    }
};
