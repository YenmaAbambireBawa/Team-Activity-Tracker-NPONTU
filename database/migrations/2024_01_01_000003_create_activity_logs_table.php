<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Each row here represents one update made to an activity by a team member.
         * Multiple updates can exist for the same activity on the same day —
         * this is intentional so the handover view shows the full update trail.
         */
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'done']);
            $table->text('remark')->nullable();
            // log_date lets us group activity logs by day for the daily view and reporting
            $table->date('log_date');
            $table->timestamps(); // created_at captures the exact time the update was made
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
