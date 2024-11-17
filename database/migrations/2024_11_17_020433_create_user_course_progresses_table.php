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
        Schema::create('user_course_progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->index('u_c_p_crsi');
            $table->foreignId('chapter_id')->nullable()->index('u_c_p_chpi');
            $table->foreignId('lesson_id')->nullable()->index('u_c_p_lsni');
            $table->foreignId('user_id')->nullable()->index('u_c_p_usi');
            $table->timestampTz('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_course_progresses');
    }
};
