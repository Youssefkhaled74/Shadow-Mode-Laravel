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
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('room_code')->unique();
            $table->string('title');
            $table->enum('scenario_type', ['interview', 'sales', 'negotiation', 'communication']);
            $table->text('description')->nullable();
            $table->foreignId('host_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('coach_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('state', ['waiting', 'live', 'paused', 'ended'])->default('waiting')->index();
            $table->timestamp('scheduled_for')->nullable()->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->json('settings')->nullable();
            $table->decimal('average_score', 5, 2)->default(0);
            $table->timestamps();

            $table->index(['host_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
