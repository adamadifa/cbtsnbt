<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamPackage;
use App\Models\Subject;
use App\Models\ExamSubtest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ExamPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamPackage::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $packages = $query->withCount('subtests')->latest()->paginate(10);

        return view('admin.exam-packages.index', compact('packages'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.exam-packages.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:free,premium',
            'price' => 'nullable|numeric|min:0',
            'show_result' => 'nullable',
            'show_explanation' => 'nullable',
            'subtests' => 'required|array|min:1',
            'subtests.*.subject_id' => 'required|exists:subjects,id',
            'subtests.*.duration_minutes' => 'required|integer|min:1',
            'subtests.*.total_questions' => 'required|integer|min:1',
            'subtests.*.order' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            $package = ExamPackage::create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
                'description' => $validated['description'],
                'type' => $validated['type'],
                'price' => $validated['type'] === 'premium' ? ($validated['price'] ?? 0) : 0,
                'is_active' => $request->has('is_active'),
                'show_result' => $request->has('show_result'),
                'show_explanation' => $request->has('show_explanation'),
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['subtests'] as $index => $subtestData) {
                $package->subtests()->create([
                    'subject_id' => $subtestData['subject_id'],
                    'duration_minutes' => $subtestData['duration_minutes'],
                    'total_questions' => $subtestData['total_questions'],
                    'order' => $subtestData['order'] ?? $index,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.exam-packages.index')->with('success', 'Paket Tryout berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat paket: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(ExamPackage $examPackage)
    {
        $examPackage->load('subtests.subject');
        $subjects = Subject::orderBy('name')->get();
        return view('admin.exam-packages.edit', compact('examPackage', 'subjects'));
    }

    public function update(Request $request, ExamPackage $examPackage)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:free,premium',
            'price' => 'nullable|numeric|min:0',
            'show_result' => 'nullable',
            'show_explanation' => 'nullable',
            'subtests' => 'required|array|min:1',
            'subtests.*.subject_id' => 'required|exists:subjects,id',
            'subtests.*.duration_minutes' => 'required|integer|min:1',
            'subtests.*.total_questions' => 'required|integer|min:1',
            'subtests.*.order' => 'nullable|integer',
            'subtests.*.db_id' => 'nullable|exists:exam_subtests,id',
        ]);

        try {
            DB::beginTransaction();

            $examPackage->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'price' => $validated['type'] === 'premium' ? ($validated['price'] ?? 0) : 0,
                'is_active' => $request->has('is_active'),
                'show_result' => $request->has('show_result'),
                'show_explanation' => $request->has('show_explanation'),
            ]);

            $submittedSubtestIds = [];
            foreach ($request->input('subtests', []) as $index => $subtestData) {
                if (!empty($subtestData['db_id'])) {
                    // Update existing subtest to preserve ID and question relationships
                    $subtest = $examPackage->subtests()->find($subtestData['db_id']);
                    if ($subtest) {
                        $subtest->update([
                            'subject_id' => $subtestData['subject_id'],
                            'duration_minutes' => $subtestData['duration_minutes'],
                            'total_questions' => $subtestData['total_questions'],
                            'order' => $subtestData['order'] ?? $index,
                        ]);
                        $submittedSubtestIds[] = $subtest->id;
                    }
                } else {
                    // Create new subtest
                    $newSubtest = $examPackage->subtests()->create([
                        'subject_id' => $subtestData['subject_id'],
                        'duration_minutes' => $subtestData['duration_minutes'],
                        'total_questions' => $subtestData['total_questions'],
                        'order' => $subtestData['order'] ?? $index,
                    ]);
                    $submittedSubtestIds[] = $newSubtest->id;
                }
            }

            // Safely delete subtests that were removed in the UI
            $examPackage->subtests()->whereNotIn('id', $submittedSubtestIds)->delete();

            DB::commit();

            return redirect()->route('admin.exam-packages.index')->with('success', 'Paket Tryout berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui paket: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(ExamPackage $examPackage)
    {
        $examPackage->delete();
        return redirect()->route('admin.exam-packages.index')->with('success', 'Paket Tryout berhasil dihapus.');
    }

    public function manageQuestions(ExamPackage $examPackage, ExamSubtest $examSubtest)
    {
        $examSubtest->load('subject', 'questions');
        $availableQuestions = \App\Models\Question::where('subject_id', $examSubtest->subject_id)->latest()->get();

        return view('admin.exam-packages.manage-questions', compact('examPackage', 'examSubtest', 'availableQuestions'));
    }

    public function updateQuestions(Request $request, ExamPackage $examPackage, ExamSubtest $examSubtest)
    {
        $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id'
        ]);

        $examSubtest->questions()->sync($request->question_ids);

        return redirect()->route('admin.exam-packages.edit', $examPackage)->with('success', 'Soal subtest berhasil diperbarui.');
    }
}
