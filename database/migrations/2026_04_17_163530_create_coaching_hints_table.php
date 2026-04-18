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
        Schema::create('coaching_hints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('target_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('category', ['confidence', 'clarity', 'pace', 'filler', 'question', 'follow_up', 'general'])->default('general');
            $table->enum('severity', ['low', 'medium', 'high'])->default('low');
            $table->boolean('is_system')->default(false);
            $table->text('content');
            $table->json('meta')->nullable();
            $table->timestamp('sent_at')->index();
            $table->timestamps();

            $table->index(['training_session_id', 'sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaching_hints');
    }
};
