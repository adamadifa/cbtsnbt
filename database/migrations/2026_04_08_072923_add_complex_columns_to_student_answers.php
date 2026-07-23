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
        Schema::table('student_answers', function (Blueprint $table) {
            $table->json('option_ids')->nullable()->after('option_id');
            $table->json('matching_answers')->nullable()->after('option_ids');
            $table->text('essay_answer')->nullable()->after('matching_answers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_answers', function (Blueprint $table) {
            $table->dropColumn(['option_ids', 'matching_answers', 'essay_answer']);
        });
    }
};
