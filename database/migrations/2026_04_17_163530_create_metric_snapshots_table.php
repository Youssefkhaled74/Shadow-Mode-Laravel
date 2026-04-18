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
        Schema::create('metric_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('confidence_score')->default(0);
            $table->unsignedTinyInteger('clarity_score')->default(0);
            $table->unsignedTinyInteger('pace_score')->default(0);
            $table->unsignedTinyInteger('overall_score')->default(0);
            $table->unsignedSmallInteger('filler_word_count')->default(0);
            $table->unsignedTinyInteger('missed_question_count')->default(0);
            $table->json('meta')->nullable();
            $table->timestamp('recorded_at')->index();
            $table->timestamps();

            $table->index(['training_session_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metric_snapshots');
    }
};
