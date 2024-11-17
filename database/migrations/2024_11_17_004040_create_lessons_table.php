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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->index('lsn_crsi');
            $table->foreignId('chapter_id')->nullable()->index('lsn_chpi');
            $table->string('title')->nullable()->index('lsn_ttl');
            $table->string('type', 10)->nullable()->index('lsn_typ');
            $table->string('video_url')->nullable();
            $table->unsignedInteger('video_duration')->nullable();
            $table->text('text_content')->nullable();
            $table->integer('order')->nullable()->index('lsn_ord');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
