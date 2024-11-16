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
        Schema::create('course_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->index('c_t_ci');
            $table->foreignId('tool_id')->nullable()->index('c_t_ti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_tools');
    }
};
