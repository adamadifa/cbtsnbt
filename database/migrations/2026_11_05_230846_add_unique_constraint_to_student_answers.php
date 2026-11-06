<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * BUG-09: Tambah unique constraint pada (exam_result_id, question_id)
     * untuk mencegah duplikasi baris akibat race condition double-submit.
     */
    public function up(): void
    {
        // Hapus duplikasi yang mungkin sudah ada (pertahankan baris terbaru)
        DB::statement('
            DELETE sa1 FROM student_answers sa1
            INNER JOIN student_answers sa2
            WHERE sa1.id < sa2.id
              AND sa1.exam_result_id = sa2.exam_result_id
              AND sa1.question_id = sa2.question_id
        ');

        Schema::table('student_answers', function (Blueprint $table) {
            $table->unique(['exam_result_id', 'question_id'], 'unique_exam_result_question');
        });
    }

    public function down(): void
    {
        Schema::table('student_answers', function (Blueprint $table) {
            $table->dropUnique('unique_exam_result_question');
        });
    }
};
