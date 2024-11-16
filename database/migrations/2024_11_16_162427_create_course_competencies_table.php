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
        Schema::create('course_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->index('c_c_crs');
            $table->string('name')->nullable()->index('c_c_n');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_competencies');
    }
};
