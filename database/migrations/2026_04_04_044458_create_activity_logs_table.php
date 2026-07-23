<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_attempt_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('action', [
                'exam_started',
                'exam_finished',
                'subtest_started',
                'subtest_finished',
                'answer_saved',
                'answer_changed',
                'question_flagged',
                'tab_switch',
                'window_blur',
                'fullscreen_exit',
                'copy_attempt',
                'right_click',
                'reconnected',
                'flagged_by_admin'
            ]);
            $table->json('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
