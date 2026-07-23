<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Penalaran Umum',
                'code' => 'PU',
                'component' => 'TPS',
                'order' => 1,
                'color' => '#3b82f6', // blue
            ],
            [
                'name' => 'Pengetahuan dan Pemahaman Umum',
                'code' => 'PPU',
                'component' => 'TPS',
                'order' => 2,
                'color' => '#10b981', // emerald
            ],
            [
                'name' => 'Pemahaman Bacaan dan Menulis',
                'code' => 'PBM',
                'component' => 'TPS',
                'order' => 3,
                'color' => '#f59e0b', // amber
            ],
            [
                'name' => 'Pengetahuan Kuantitatif',
                'code' => 'PK',
                'component' => 'TPS',
                'order' => 4,
                'color' => '#ef4444', // red
            ],
            [
                'name' => 'Literasi dalam Bahasa Indonesia',
                'code' => 'LBI',
                'component' => 'Literasi',
                'order' => 5,
                'color' => '#8b5cf6', // violet
            ],
            [
                'name' => 'Literasi dalam Bahasa Inggris',
                'code' => 'LBE',
                'component' => 'Literasi',
                'order' => 6,
                'color' => '#ec4899', // pink
            ],
            [
                'name' => 'Penalaran Matematika',
                'code' => 'PM',
                'component' => 'Literasi',
                'order' => 7,
                'color' => '#06b6d4', // cyan
            ],
        ];

        foreach ($subjects as $subject) {
            \App\Models\Subject::create($subject);
        }
    }
}
