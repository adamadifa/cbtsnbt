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
        Schema::create('campus_prodis', function (Blueprint $table) {
            $table->id();
            $table->string('campus_name');
            $table->string('prodi_name');
            $table->string('jenjang');
            $table->timestamps();
            
            $table->index('campus_name');
            $table->index('prodi_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campus_prodis');
    }
};
