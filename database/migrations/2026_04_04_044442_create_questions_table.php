<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->enum('type', [
                'pilihan_ganda',
                'essai',
                'listening',
                'pilihan_ganda_kompleks',
                'menjodohkan',
                'isian_singkat',
                'mengurutkan',
                'benar_salah',
                'setuju_tidak'
            ]);
            $table->longText('content');
            $table->longText('explanation')->nullable();
            $table->string('audio_file')->nullable();
            $table->string('image')->nullable();
            $table->enum('difficulty', ['mudah', 'sedang', 'sulit'])->default('sedang');
            $table->decimal('points', 5, 2)->default(1);
            $table->decimal('negative_points', 5, 2)->default(0);
            $table->longText('passage_text')->nullable();
            $table->foreignId('passage_group_id')->nullable()->constrained('passage_groups')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
