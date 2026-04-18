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
        Schema::create('report_timeline_moments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_report_id');
            $table->foreignId('training_session_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('timestamp_seconds')->default(0);
            $table->enum('type', ['highlight', 'warning', 'mistake', 'improvement'])->default('highlight');
            $table->string('title');
            $table->text('description')->nullable();
            $table->tinyInteger('impact_score')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['session_report_id', 'timestamp_seconds']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_timeline_moments');
    }
};
