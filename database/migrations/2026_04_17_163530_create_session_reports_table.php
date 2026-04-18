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
        Schema::create('session_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('overall_score')->default(0);
            $table->text('summary')->nullable();
            $table->text('best_response')->nullable();
            $table->text('weakest_response')->nullable();
            $table->json('key_mistakes')->nullable();
            $table->json('improvement_suggestions')->nullable();
            $table->json('score_breakdown')->nullable();
            $table->timestamp('generated_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_reports');
    }
};
