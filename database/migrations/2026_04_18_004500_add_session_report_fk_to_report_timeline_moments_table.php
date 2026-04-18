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
        Schema::table('report_timeline_moments', function (Blueprint $table) {
            $table->foreign('session_report_id')
                ->references('id')
                ->on('session_reports')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_timeline_moments', function (Blueprint $table) {
            $table->dropForeign(['session_report_id']);
        });
    }
};

