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
        \DB::table('settings')->insert([
            'key' => 'next_subtest_delay',
            'value' => '10',
            'type' => 'text',
            'group' => 'ujian',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('settings')->where('key', 'next_subtest_delay')->delete();
    }
};
