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
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->boolean('available')->default(false);
            $table->string('day');
            $table->time('from')->default('00:00');
            $table->time('to')->default('23:59');
            $table->time('break_from')->nullable();
            $table->time('break_to')->nullable();
            $table->time('total_break_time')->nullable();
            $table->unsignedBigInteger('seeker_id');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('seeker_id')->references('id')->on('seekers')->onDelete('cascade');
        });

        // Add the 'total_time' column after creating the table
        Schema::table('days', function (Blueprint $table) {
            $table->string('total_time')->nullable()->after('to');
        });
    }

    public function down()
    {
        Schema::dropIfExists('days');
    }

};
