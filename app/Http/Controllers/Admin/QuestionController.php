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
            return redirect()->route('admin.questions.index', ['subject_id' => $request->subject_id])->with('success', 'Soal berhasil ditambahkan.');
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
            return redirect()->route('admin.questions.index', ['subject_id' => $request->subject_id])->with('success', 'Soal berhasil diperbarui.');
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

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:questions,id',
        ]);

        Question::destroy($request->ids);

        return back()->with('success', count($request->ids) . ' soal berhasil dihapus.');
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

        // Header - menggunakan addText dengan font style bold & besar
        $headerFont = ['bold' => true, 'size' => 16];
        $boldFont = ['bold' => true, 'size' => 11];
        $normalFont = ['size' => 10];

        $section->addText('Template Import Soal (.docx)', $headerFont);
        $section->addTextBreak(1);

        $section->addText('PETUNJUK PENGGUNAAN', $boldFont);
        $section->addText('Gunakan penanda berikut untuk memisahkan setiap bagian:', $normalFont);
        $section->addTextBreak(1);
        $section->addText('[SOAL] - Isi pertanyaan', $normalFont);
        $section->addText('[A], [B], [C], dst. - Pernyataan atau opsi jawaban', $normalFont);
        $section->addText('    Khusus tipe menjodohkan, tulis: Kiri = Kanan. Contoh: [A] Jakarta = Indonesia', $normalFont);
        $section->addText('[KUNCI] - Label kunci jawaban (A/B/C/dst)', $normalFont);
        $section->addText('    Untuk pilihan_ganda_kompleks, pisahkan koma. Contoh: A, C', $normalFont);
        $section->addText('    Untuk benar_salah, cantumkan label yang BENAR. Contoh: A, C', $normalFont);
        $section->addText('    Untuk menjodohkan dan essai, kosongkan saja', $normalFont);
        $section->addText('[TIPE] - pilihan_ganda / pilihan_ganda_kompleks / essai / menjodohkan / benar_salah', $normalFont);
        $section->addText('[POIN] - Angka poin soal (misal: 1 atau 5)', $normalFont);
        $section->addText('[KESULITAN] - mudah / sedang / sulit', $normalFont);
        $section->addText('[PEMBAHASAN] - Penjelasan soal', $normalFont);
        $section->addText('Gunakan --- (tiga strip) sebagai pemisah antar soal.', $normalFont);
        $section->addTextBreak(2);

        // ============ CONTOH 1: Pilihan Ganda ============
        $section->addText('CONTOH 1: PILIHAN GANDA', $boldFont);
        $section->addText('[TIPE] pilihan_ganda', $normalFont);
        $section->addText('[POIN] 1', $normalFont);
        $section->addText('[KESULITAN] mudah', $normalFont);
        $section->addText('[SOAL] Berikut ini adalah contoh soal Pilihan Ganda?', $normalFont);
        $section->addText('[A] Pilihan Pertama (Benar)', $normalFont);
        $section->addText('[B] Pilihan Kedua', $normalFont);
        $section->addText('[C] Pilihan Ketiga', $normalFont);
        $section->addText('[D] Pilihan Keempat', $normalFont);
        $section->addText('[KUNCI] A', $normalFont);
        $section->addText('[PEMBAHASAN] Pembahasan kunci jawaban A.', $normalFont);
        $section->addText('---', $normalFont);
        $section->addTextBreak(1);

        // ============ CONTOH 2: Pilihan Ganda Kompleks ============
        $section->addText('CONTOH 2: PILIHAN GANDA KOMPLEKS', $boldFont);
        $section->addText('[TIPE] pilihan_ganda_kompleks', $normalFont);
        $section->addText('[POIN] 2', $normalFont);
        $section->addText('[KESULITAN] sedang', $normalFont);
        $section->addText('[SOAL] Pilihlah dua pernyataan yang benar di bawah ini!', $normalFont);
        $section->addText('[A] Pernyataan Pertama (Benar)', $normalFont);
        $section->addText('[B] Pernyataan Kedua (Salah)', $normalFont);
        $section->addText('[C] Pernyataan Ketiga (Benar)', $normalFont);
        $section->addText('[D] Pernyataan Keempat (Salah)', $normalFont);
        $section->addText('[KUNCI] A, C', $normalFont);
        $section->addText('[PEMBAHASAN] Kunci jawaban yang benar adalah A dan C.', $normalFont);
        $section->addText('---', $normalFont);
        $section->addTextBreak(1);

        // ============ CONTOH 3: Benar / Salah ============
        $section->addText('CONTOH 3: BENAR / SALAH', $boldFont);
        $section->addText('[TIPE] benar_salah', $normalFont);
        $section->addText('[POIN] 2', $normalFont);
        $section->addText('[KESULITAN] sedang', $normalFont);
        $section->addText('[SOAL] Tentukan nilai kebenaran dari pernyataan berikut!', $normalFont);
        $section->addText('[A] Air mendidih pada suhu 100 derajat Celcius.', $normalFont);
        $section->addText('[B] Matahari berputar mengelilingi bumi.', $normalFont);
        $section->addText('[C] Indonesia merdeka pada tahun 1945.', $normalFont);
        $section->addText('[KUNCI] A, C', $normalFont);
        $section->addText('[PEMBAHASAN] Opsi A dan C bernilai Benar, sedangkan Opsi B bernilai Salah.', $normalFont);
        $section->addText('---', $normalFont);
        $section->addTextBreak(1);

        // ============ CONTOH 4: Menjodohkan ============
        $section->addText('CONTOH 4: MENJODOHKAN', $boldFont);
        $section->addText('[TIPE] menjodohkan', $normalFont);
        $section->addText('[POIN] 3', $normalFont);
        $section->addText('[KESULITAN] sulit', $normalFont);
        $section->addText('[SOAL] Jodohkanlah negara dengan ibukotanya!', $normalFont);
        $section->addText('[A] Indonesia = Jakarta', $normalFont);
        $section->addText('[B] Jepang = Tokyo', $normalFont);
        $section->addText('[C] Perancis = Paris', $normalFont);
        $section->addText('[PEMBAHASAN] Penjelasan pasangan menjodohkan yang tepat.', $normalFont);
        $section->addText('---', $normalFont);
        $section->addTextBreak(1);

        // ============ CONTOH 5: Essai ============
        $section->addText('CONTOH 5: ESSAI', $boldFont);
        $section->addText('[TIPE] essai', $normalFont);
        $section->addText('[POIN] 5', $normalFont);
        $section->addText('[KESULITAN] sedang', $normalFont);
        $section->addText('[SOAL] Jelaskan apa yang dimaksud dengan fotosintesis pada tumbuhan!', $normalFont);
        $section->addText('[PEMBAHASAN] Jawaban essai harus menjelaskan proses konversi cahaya matahari menjadi energi kimia oleh tumbuhan.', $normalFont);
        $section->addText('---', $normalFont);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), 'template_soal');
        $objWriter->save($tempFile);

        return response()->download($tempFile, 'template_import_soal_cbt.docx')->deleteFileAfterSend(true);
    }
}

