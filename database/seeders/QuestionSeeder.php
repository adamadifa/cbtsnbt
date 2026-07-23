<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $subjects = Subject::all();

        if ($subjects->isEmpty()) {
            $this->command->warn('No subjects found. Please seed subjects first.');
            return;
        }

        $questions = [
            [
                'subject_id' => 1, // Penalaran Umum
                'type' => 'pilihan_ganda',
                'content' => '<p>Semua mahasiswa yang lulus ujian tepat waktu mendapatkan beasiswa. Sebagian mahasiswa yang mendapatkan beasiswa mengikuti program magang di luar negeri. Jadi...</p>',
                'explanation' => '<p>Logika: Semua A adalah B. Sebagian B adalah C. Tidak dapat disimpulkan hubungan antara A dan C secara mutlak, namun sebagian penerima beasiswa (yang mungkin saja mahasiswa lulus tepat waktu) mengikuti magang.</p>',
                'difficulty' => 'sedang',
                'points' => 1.0,
                'options' => [
                    ['label' => 'A', 'content' => 'Semua mahasiswa lulus tepat waktu ikut magang', 'is_correct' => false],
                    ['label' => 'B', 'content' => 'Sebagian mahasiswa yang mendapatkan beasiswa lulus tepat waktu', 'is_correct' => true],
                    ['label' => 'C', 'content' => 'Mahasiswa yang tidak lulus tepat waktu tidak dapat beasiswa', 'is_correct' => false],
                    ['label' => 'D', 'content' => 'Sebagian mahasiswa magang luar negeri tidak dapat beasiswa', 'is_correct' => false],
                    ['label' => 'E', 'content' => 'Semua mahasiswa magang luar negeri lulus tepat waktu', 'is_correct' => false],
                ]
            ],
            [
                'subject_id' => 7, // Penalaran Matematika
                'type' => 'pilihan_ganda',
                'content' => '<p>Sebuah tangki air berbentuk silinder dengan jari-jari 70 cm dan tinggi 2 meter. Jika tangki tersebut terisi air 3/4 bagian, berapa liter volume air dalam tangki? (π = 22/7)</p>',
                'explanation' => '<p>Volume = π r² t = (22/7) * 70 * 70 * 200 = 3,080,000 cm³ = 3,080 Liter. <br>3/4 Volume = 3/4 * 3,080 = 2,310 Liter.</p>',
                'difficulty' => 'sulit',
                'points' => 1.0,
                'options' => [
                    ['label' => 'A', 'content' => '1.540 Liter', 'is_correct' => false],
                    ['label' => 'B', 'content' => '2.310 Liter', 'is_correct' => true],
                    ['label' => 'C', 'content' => '3.080 Liter', 'is_correct' => false],
                    ['label' => 'D', 'content' => '4.620 Liter', 'is_correct' => false],
                    ['label' => 'E', 'content' => '2.130 Liter', 'is_correct' => false],
                ]
            ],
            [
                'subject_id' => 4, // Pengetahuan Kuantitatif
                'type' => 'pilihan_ganda_kompleks',
                'content' => '<p>Manakah dari pernyataan berikut yang benar mengenai bilangan prima?</p>',
                'explanation' => '<p>Bilangan prima adalah bilangan yang hanya memiliki 2 faktor (1 dan dirinya sendiri). 1 bukan prima. 2 adalah satu-satunya prima genap.</p>',
                'difficulty' => 'mudah',
                'points' => 1.0,
                'options' => [
                    ['label' => 'A', 'content' => 'Bilangan 2 adalah bilangan prima genap', 'is_correct' => true],
                    ['label' => 'B', 'content' => 'Semua bilangan ganjil adalah bilangan prima', 'is_correct' => false],
                    ['label' => 'C', 'content' => 'Bilangan 1 merupakan bilangan prima terkecil', 'is_correct' => false],
                    ['label' => 'D', 'content' => 'Bilangan prima hanya memiliki dua faktor', 'is_correct' => true],
                    ['label' => 'E', 'content' => '9 adalah bilangan prima', 'is_correct' => false],
                ]
            ],
            [
                'subject_id' => 5, // Literasi Bahasa Indonesia
                'type' => 'pilihan_ganda',
                'content' => '<p><strong>Bacalah teks berikut!</strong><br>Pemerintah berencana menaikkan tarif pajak pertambahan nilai (PPn) mulai tahun depan untuk menjaga stabilitas fiskal. Namun, langkah ini dikhawatirkan akan menekan daya beli masyarakat yang baru saja pulih pascapandemi.</p><p>Apa inti dari paragraf di atas?</p>',
                'explanation' => '<p>Inti kalimat adalah subjek dan predikat utama: Rencana pemerintah menaikkan PPn dan dampaknya terhadap masyarakat.</p>',
                'difficulty' => 'sedang',
                'points' => 1.0,
                'options' => [
                    ['label' => 'A', 'content' => 'Daya beli masyarakat pascapandemi', 'is_correct' => false],
                    ['label' => 'B', 'content' => 'Stabilitas fiskal pemerintah', 'is_correct' => false],
                    ['label' => 'C', 'content' => 'Dampak kenaikan tarif PPn terhadap daya beli', 'is_correct' => true],
                    ['label' => 'D', 'content' => 'Tarif pajak baru tahun depan', 'is_correct' => false],
                    ['label' => 'E', 'content' => 'Pemulihan ekonomi pascapandemi', 'is_correct' => false],
                ]
            ],
        ];

        foreach ($questions as $qData) {
            $question = Question::create([
                'subject_id' => $qData['subject_id'],
                'type' => $qData['type'],
                'content' => $qData['content'],
                'explanation' => $qData['explanation'],
                'difficulty' => $qData['difficulty'],
                'points' => $qData['points'],
                'is_active' => true,
                'created_by' => 1,
            ]);

            foreach ($qData['options'] as $optData) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'label' => $optData['label'],
                    'content' => $optData['content'],
                    'is_correct' => $optData['is_correct'],
                ]);
            }
        }
    }
}
