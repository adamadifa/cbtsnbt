<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\PassageGroup;
use App\Models\ExamPackage;
use App\Models\ExamSubtest;

class FixQuestionHtmlEntities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'questions:fix-entities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $signatureDescription = 'Membersihkan entitas HTML seperti &quot;, &#039;, &gt;, &lt; pada bank soal dan opsi jawaban.';

    /**
     * Clean HTML entities from string.
     */
    public function cleanText(?string $str): ?string
    {
        if ($str === null || $str === '') {
            return $str;
        }

        // 1. Double escaped entities
        $searchDouble = [
            '&amp;quot;',
            '&amp;#039;',
            '&amp;apos;',
            '&amp;gt;',
            '&amp;lt;',
            '&amp;amp;',
            '&amp;nbsp;',
        ];
        $replaceDouble = [
            '"',
            "'",
            "'",
            '>',
            '<',
            '&',
            ' ',
        ];
        $str = str_replace($searchDouble, $replaceDouble, $str);

        // 2. Single escaped quote & display entities
        $searchSingle = [
            '&quot;',
            '&#039;',
            '&apos;',
            '-&gt;',
            '=&gt;',
            '&gt;=',
            '&lt;=',
        ];
        $replaceSingle = [
            '"',
            "'",
            "'",
            '->',
            '=>',
            '>=',
            '<=',
        ];
        $str = str_replace($searchSingle, $replaceSingle, $str);

        return $str;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai perbaikan teks entitas HTML pada database...');

        $updatedQuestions = 0;
        foreach (Question::all() as $q) {
            $newContent = $this->cleanText($q->content);
            $newExpl = $this->cleanText($q->explanation);

            if ($newContent !== $q->content || $newExpl !== $q->explanation) {
                $q->content = $newContent;
                $q->explanation = $newExpl;
                $q->save();
                $updatedQuestions++;
            }
        }

        $updatedOptions = 0;
        foreach (QuestionOption::all() as $o) {
            $newContent = $this->cleanText($o->content);
            $newLabel = $this->cleanText($o->label);

            if ($newContent !== $o->content || $newLabel !== $o->label) {
                $o->content = $newContent;
                $o->label = $newLabel;
                $o->save();
                $updatedOptions++;
            }
        }

        $updatedPassages = 0;
        foreach (PassageGroup::all() as $p) {
            $newTitle = $this->cleanText($p->title);
            $newContent = $this->cleanText($p->content);

            if ($newTitle !== $p->title || $newContent !== $p->content) {
                $p->title = $newTitle;
                $p->content = $newContent;
                $p->save();
                $updatedPassages++;
            }
        }

        $this->info("Selesai! Berhasil memperbaiki:");
        $this->line("- {$updatedQuestions} butir Soal");
        $this->line("- {$updatedOptions} Pilihan Jawaban");
        $this->line("- {$updatedPassages} Wacana/Stimulus");

        return Command::SUCCESS;
    }
}
