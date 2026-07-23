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
        Schema::table('exam_results', function (Blueprint $table) {
            $table->index(['user_id', 'exam_session_id']);
            $table->index('status');
        });

        Schema::table('student_answers', function (Blueprint $table) {
            $table->index('exam_result_id');
            $table->index('question_id');
        });

        Schema::table('exam_violations', function (Blueprint $table) {
            $table->index('exam_result_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'exam_session_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('student_answers', function (Blueprint $table) {
            $table->dropIndex(['exam_result_id']);
            $table->dropIndex(['question_id']);
        });

        Schema::table('exam_violations', function (Blueprint $table) {
            $table->dropIndex(['exam_result_id']);
        });
    }
};
