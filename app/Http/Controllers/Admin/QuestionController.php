<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use App\Models\PassageGroup;
use App\Services\WordImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    protected $wordImportService;

    public function __construct(WordImportService $wordImportService)
    {
        $this->wordImportService = $wordImportService;
    }

    public function index(Request $request)
    {
        $query = Question::with(['subject', 'options']);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('search')) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(10)->withQueryString();
        $subjects = Subject::withCount('questions')->orderBy('name')->get();

        return view('admin.questions.index', compact('questions', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        $passageGroups = PassageGroup::orderBy('title')->get();
        return view('admin.questions.create', compact('subjects', 'passageGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'type' => 'required',
            'content' => 'required',
            'points' => 'required|numeric|min:0',
            'options.*.content' => 'required_if:type,pilihan_ganda,pilihan_ganda_kompleks',
            'match_options.*.right' => 'required_if:type,menjodohkan',
        ]);

        try {
            DB::beginTransaction();

            $question = Question::create([
                'subject_id' => $request->subject_id,
                'type' => $request->type,
                'content' => $request->content,
                'explanation' => $request->explanation,
                'difficulty' => $request->difficulty ?? 'sedang',
                'points' => $request->points,
                'negative_points' => $request->negative_points ?? 0,
                'passage_group_id' => $request->passage_group_id,
                'is_active' => $request->has('is_active'),
                'created_by' => auth()->id(),
            ]);

            if (in_array($request->type, ['pilihan_ganda', 'pilihan_ganda_kompleks'])) {
                foreach ($request->options as $label => $opt) {
                    $question->options()->create([
                        'label' => $label,
                        'content' => $opt['content'],
                        'is_correct' => isset($opt['is_correct']),
                    ]);
                }
            } elseif ($request->type === 'menjodohkan') {
                foreach ($request->match_options as $opt) {
                    $question->options()->create([
                        'label' => $opt['left'],
                        'content' => $opt['right'],
                        'is_correct' => true,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.questions.index')->with('success', 'Soal berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan soal: ' . $e->getMessage());
        }
    }

    public function show(Question $question)
    {
        $question->load(['subject', 'options', 'passageGroup']);
        return view('admin.questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
        $question->load('options');
        $subjects = Subject::orderBy('name')->get();
        $passageGroups = PassageGroup::orderBy('title')->get();
        return view('admin.questions.edit', compact('question', 'subjects', 'passageGroups'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'type' => 'required',
            'content' => 'required',
            'points' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $question->update([
                'subject_id' => $request->subject_id,
                'type' => $request->type,
                'content' => $request->content,
                'explanation' => $request->explanation,
                'difficulty' => $request->difficulty,
                'points' => $request->points,
                'negative_points' => $request->negative_points ?? 0,
                'passage_group_id' => $request->passage_group_id,
                'is_active' => $request->has('is_active'),
            ]);

            if (in_array($request->type, ['pilihan_ganda', 'pilihan_ganda_kompleks'])) {
                // For simplicity in this demo, delete and recreate options
                $question->options()->delete();
                foreach ($request->options as $label => $opt) {
                    $question->options()->create([
                        'label' => $label,
                        'content' => $opt['content'],
                        'is_correct' => isset($opt['is_correct']),
                    ]);
                }
            } elseif ($request->type === 'menjodohkan') {
                $question->options()->delete();
                foreach ($request->match_options as $opt) {
                    $question->options()->create([
                        'label' => $opt['left'],
                        'content' => $opt['right'],
                        'is_correct' => true,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.questions.index')->with('success', 'Soal berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui soal: ' . $e->getMessage());
        }
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    public function importWord(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'word_file' => 'required|mimes:docx|max:10240',
        ]);

        try {
            $path = $request->file('word_file')->getRealPath();
            $count = $this->wordImportService->import($path, $request->subject_id);

            return redirect()->route('admin.questions.index')
                ->with('success', "$count soal berhasil diimport dari file Word.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport file Word: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        
        $section->addTitle('Template Import Soal (.docx)', 1);
        $section->addText('Petunjuk: Gunakan penanda berikut untuk memisahkan setiap bagian:');
        $section->addListItem('[SOAL] : Isi pertanyaan');
        $section->addListItem('[A], [B], [C], [D], [E] : Pilihan jawaban');
        $section->addListItem('[KUNCI] : Label kunci (A/B/C/D/E). Pisahkan koma jika jawaban lebih dari satu.');
        $section->addListItem('[TIPE] : pilihan_ganda, pilihan_ganda_kompleks, essai');
        $section->addListItem('[POIN] : Angka (misal: 1 atau 2.5)');
        $section->addListItem('[KESULITAN] : mudah, sedang, sulit');
        $section->addListItem('[PEMBAHASAN] : Penjelasan soal');
        $section->addText('Gunakan "---" sebagai pemisah antar soal.');
        $section->addTextBreak(2);

        $section->addText('[TIPE] pilihan_ganda');
        $section->addText('[POIN] 2');
        $section->addText('[KESULITAN] sedang');
        $section->addText('[SOAL]');
        $section->addText('Berikut ini adalah contoh soal pertama? Anda bisa menambahkan tabel atau gambar di sini.');
        $section->addText('[A] Pilihan Pertama');
        $section->addText('[B] Pilihan Kedua');
        $section->addText('[C] Pilihan Ketiga');
        $section->addText('[D] Pilihan Keempat');
        $section->addText('[E] Pilihan Kelima');
        $section->addText('[KUNCI] A');
        $section->addText('[PEMBAHASAN]');
        $section->addText('Tuliskan penjelasan jawaban di sini.');
        $section->addText('---');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), 'template_soal');
        $objWriter->save($tempFile);

        return response()->download($tempFile, 'template_import_soal_cbt.docx')->deleteFileAfterSend(true);
    }
}
