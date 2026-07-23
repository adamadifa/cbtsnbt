<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subtest_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->enum('answer_type', [
                'single_choice',
                'multiple_choice',
                'text',
                'matching',
                'ordering',
                'listening_choice'
            ]);
            $table->foreignId('selected_option_id')->nullable()->constrained('question_options')->nullOnDelete();
            $table->json('selected_options')->nullable();
            $table->longText('text_answer')->nullable();
            $table->json('matching_answer')->nullable();
            $table->json('ordering_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('points_earned', 5, 2)->default(0);
            $table->boolean('is_marked')->default(false);
            $table->timestamp('answered_at')->nullable();
            $table->integer('time_spent_seconds')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
