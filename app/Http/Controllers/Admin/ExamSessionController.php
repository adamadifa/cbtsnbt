<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\ExamPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Events\MonitorExamUpdated;

class ExamSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamSession::with('examPackage');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('token', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sessions = $query->latest()->paginate(10);

        return view('admin.exam-sessions.index', compact('sessions'));
    }

    public function create()
    {
        $packages = ExamPackage::where('is_active', true)->get();
        return view('admin.exam-sessions.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_package_id' => 'required|exists:exam_packages,id',
            'title' => 'required|string|max:255',
            'token' => 'nullable|string|max:20|alpha_num|unique:exam_sessions,token',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean'
        ]);

        try {
            DB::beginTransaction();

            $token = $request->token ? strtoupper($request->token) : null;
            if (!$token) {
                do {
                    $token = strtoupper(Str::random(6));
                } while (ExamSession::where('token', $token)->exists());
            }

            $session = ExamSession::create([
                'exam_package_id' => $request->exam_package_id,
                'title' => $request->title,
                'token' => $token,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'max_participants' => $request->max_participants,
                'is_active' => $request->has('is_active'),
                'status' => 'scheduled',
                'created_by' => auth()->id()
            ]);

            DB::commit();

            return redirect()->route('admin.exam-sessions.index')
                ->with('success', 'Sesi ujian berhasil dijadwalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menjadwalkan sesi: ' . $e->getMessage());
        }
    }

    public function edit(ExamSession $examSession)
    {
        $packages = ExamPackage::where('is_active', true)->get();
        return view('admin.exam-sessions.edit', compact('examSession', 'packages'));
    }

    public function update(Request $request, ExamSession $examSession)
    {
        $request->validate([
            'exam_package_id' => 'required|exists:exam_packages,id',
            'title' => 'required|string|max:255',
            'token' => 'nullable|string|max:20|alpha_num|unique:exam_sessions,token,' . $examSession->id,
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'status' => 'required|in:scheduled,active,completed,cancelled'
        ]);

        try {
            DB::beginTransaction();

            $examSession->update([
                'exam_package_id' => $request->exam_package_id,
                'title' => $request->title,
                'token' => strtoupper($request->token ?? $examSession->token),
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'max_participants' => $request->max_participants,
                'is_active' => $request->has('is_active'),
                'status' => $request->status
            ]);

            DB::commit();

            return redirect()->route('admin.exam-sessions.index')
                ->with('success', 'Sesi ujian berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui sesi: ' . $e->getMessage());
        }
    }

    public function destroy(ExamSession $examSession)
    {
        $examSession->delete();
        return back()->with('success', 'Sesi ujian berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'session_ids' => 'required|array',
            'session_ids.*' => 'exists:exam_sessions,id'
        ]);

        $count = count($request->session_ids);
        ExamSession::whereIn('id', $request->session_ids)->delete();

        return redirect()->route('admin.exam-sessions.index')->with('success', "{$count} sesi ujian berhasil dihapus.");
    }

    public function show(ExamSession $examSession)
    {
        $examSession->load(['examPackage.subtests.subject', 'results.user']);
        
        $results = \App\Models\ExamResult::with(['user.targets.campusProdi', 'answers', 'violations'])
            ->where('exam_session_id', $examSession->id)
            ->orderByRaw("FIELD(status, 'completed', 'in_progress')")
            ->orderByDesc('total_score')
            ->orderByDesc('started_at')
            ->get();

        $completedResults = $results->where('status', 'completed');

        // Calculate basic statistics
        $stats = [
            'total_participants' => $results->count(),
            'completed' => $completedResults->count(),
            'in_progress' => $results->where('status', 'in_progress')->count(),
            'avg_score' => $completedResults->avg('total_score') ?? 0,
            'highest_score' => $completedResults->max('total_score') ?? 0,
        ];

        // Complex Analytics: Subtest Averages
        $subtestStats = [];
        $subtests = $examSession->examPackage->subtests->sortBy('order');
        
        foreach ($subtests as $subtest) {
            $subtestQuestionIds = $subtest->questions()
                ->take($subtest->total_questions)
                ->pluck('questions.id');
            
            $avgCorrect = 0;
            if ($completedResults->count() > 0) {
                // Hitung jumlah jawaban benar (bukan sum points) agar merefleksikan rata-rata jumlah soal benar
                $totalCorrect = \App\Models\StudentAnswer::whereIn('exam_result_id', $completedResults->pluck('id'))
                    ->whereIn('question_id', $subtestQuestionIds)
                    ->where('is_correct', true)
                    ->count();
                
                $avgCorrect = $totalCorrect / $completedResults->count();
            }

            $subtestStats[] = [
                'title' => $subtest->title ?: ($subtest->subject->name ?? 'Subtest'),
                'avg_correct' => round($avgCorrect, 1),
                'total_questions' => $subtest->total_questions,
                'percentage' => $subtest->total_questions > 0 ? round(($avgCorrect / $subtest->total_questions) * 100, 1) : 0
            ];
        }

        // Score Distribution (for Charts)
        $distribution = [
            '0-20' => 0, '21-40' => 0, '41-60' => 0, '61-80' => 0, '81-100' => 0
        ];

        // Hitung total poin maksimal sebenarnya dari seluruh soal untuk normalisasi persentase
        $allExamQuestionIds = [];
        foreach ($subtests as $subtest) {
            $ids = $subtest->questions()->take($subtest->total_questions)->pluck('questions.id')->toArray();
            $allExamQuestionIds = array_merge($allExamQuestionIds, $ids);
        }
        $maxPossiblePoints = \App\Models\Question::whereIn('id', $allExamQuestionIds)->sum('points');
        if ($maxPossiblePoints <= 0) {
            $maxPossiblePoints = $subtests->sum('total_questions');
        }
        
        foreach ($completedResults as $result) {
            $percentage = $maxPossiblePoints > 0 ? ($result->total_score / $maxPossiblePoints) * 100 : 0;
            $percentage = min(100, max(0, $percentage));
            
            if ($percentage <= 20) $distribution['0-20']++;
            elseif ($percentage <= 40) $distribution['21-40']++;
            elseif ($percentage <= 60) $distribution['41-60']++;
            elseif ($percentage <= 80) $distribution['61-80']++;
            else $distribution['81-100']++;
        }

        // Prepare detailed matrix data (Subtest -> Questions)
        $matrixSubtests = [];
        foreach ($subtests as $subtest) {
            $questions = $subtest->questions()
                ->take($subtest->total_questions)
                ->orderBy('exam_subtest_questions.order')
                ->get();
            
            $matrixSubtests[] = [
                'id' => $subtest->id,
                'title' => $subtest->title ?: ($subtest->subject->name ?? 'Subtest'),
                'questions' => $questions
            ];
        }

        if (request()->ajax()) {
            return view('admin.exam-sessions.partials.monitor-data', compact('examSession', 'results', 'stats', 'subtestStats', 'distribution', 'matrixSubtests'));
        }

        return view('admin.exam-sessions.show', compact('examSession', 'results', 'stats', 'subtestStats', 'distribution', 'matrixSubtests'));
    }

    public function resetStudent(ExamSession $examSession, \App\Models\ExamResult $examResult)
    {
        if ($examResult->exam_session_id !== $examSession->id) {
            return back()->with('error', 'Data tidak valid.');
        }

        // Deleting the exam result cascades to student answers
        $examResult->delete();

        event(new MonitorExamUpdated($examSession->id));

        return back()->with('success', 'Ujian siswa berhasil di-reset. Siswa dapat login kembali dengan token yang sama.');
    }

    public function forceFinishStudent(ExamSession $examSession, \App\Models\ExamResult $examResult)
    {
        if ($examResult->exam_session_id !== $examSession->id) {
            return back()->with('error', 'Data tidak valid.');
        }

        if ($examResult->status === 'completed') {
            return back()->with('error', 'Siswa sudah menyelesaikan ujian.');
        }

        DB::transaction(function () use ($examResult) {
            $totalScore = \App\Models\StudentAnswer::where('exam_result_id', $examResult->id)->sum('points');

            $examResult->update([
                'status' => 'completed',
                'finished_at' => now(),
                'total_score' => $totalScore
            ]);
        });

        event(new MonitorExamUpdated($examSession->id));

        return back()->with('success', 'Ujian siswa berhasil diselesaikan secara paksa.');
    }

    public function bulkForceFinish(Request $request, ExamSession $examSession)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:exam_results,id'
        ]);

        $ids = $request->input('ids');

        DB::transaction(function () use ($ids, $examSession) {
            foreach ($ids as $id) {
                $examResult = \App\Models\ExamResult::where('id', $id)
                    ->where('exam_session_id', $examSession->id)
                    ->where('status', 'in_progress')
                    ->first();

                if ($examResult) {
                    $totalScore = \App\Models\StudentAnswer::where('exam_result_id', $examResult->id)->sum('points');
                    $examResult->update([
                        'status' => 'completed',
                        'finished_at' => now(),
                        'total_score' => $totalScore
                    ]);
                }
            }
        });

        event(new MonitorExamUpdated($examSession->id));

        return response()->json([
            'success' => true,
            'message' => 'Ujian siswa terpilih berhasil diselesaikan.'
        ]);
    }

    public function bulkReset(Request $request, ExamSession $examSession)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:exam_results,id'
        ]);

        $ids = $request->input('ids');

        DB::transaction(function () use ($ids, $examSession) {
            \App\Models\ExamResult::whereIn('id', $ids)
                ->where('exam_session_id', $examSession->id)
                ->delete();
        });

        event(new MonitorExamUpdated($examSession->id));

        return response()->json([
            'success' => true,
            'message' => 'Ujian siswa terpilih berhasil di-reset.'
        ]);
    }

    public function exportExcel(ExamSession $examSession)
    {
        $results = \App\Models\ExamResult::with(['user.targets.campusProdi', 'answers', 'violations'])
            ->where('exam_session_id', $examSession->id)
            ->orderByDesc('total_score')
            ->get();

        $subtests = $examSession->examPackage->subtests->sortBy('order');
        $matrixSubtests = [];
        foreach ($subtests as $subtest) {
            $questions = $subtest->questions()
                ->take($subtest->total_questions)
                ->orderBy('exam_subtest_questions.order')
                ->get();
            
            $matrixSubtests[] = [
                'id' => $subtest->id,
                'title' => $subtest->title ?: ($subtest->subject->name ?? 'Subtest'),
                'questions' => $questions
            ];
        }

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ExamResultsExport($results, $matrixSubtests), 
            'hasil_ujian_' . Str::slug($examSession->title) . '.xlsx'
        );
    }

    public function exportPdf(ExamSession $examSession)
    {
        $results = \App\Models\ExamResult::with(['user.targets.campusProdi', 'violations'])
            ->where('exam_session_id', $examSession->id)
            ->orderByDesc('total_score')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exam-sessions.pdf-results', [
            'session' => $examSession,
            'results' => $results
        ]);

        return $pdf->setPaper('a4', 'landscape')->download('hasil_ujian_' . Str::slug($examSession->title) . '.pdf');
    }

    public function studentResults(ExamSession $examSession, \App\Models\ExamResult $examResult)
    {
        if ($examResult->exam_session_id !== $examSession->id) {
            abort(404);
        }

        $session = $examSession->load('examPackage.subtests.questions.options');
        $package = $session->examPackage;

        $subtests = $package->subtests;
        $answers = \App\Models\StudentAnswer::where('exam_result_id', $examResult->id)->get();

        // Calculate Stats
        $stats = [
            'total_questions' => $subtests->sum(fn($s) => min($s->total_questions, $s->questions->count())),
            'answered' => $answers->count(),
            'correct' => $answers->where('is_correct', true)->count(),
            'wrong' => $answers->where('is_correct', false)->count(),
            'empty' => 0,
        ];
        $stats['empty'] = $stats['total_questions'] - $stats['answered'];
        $stats['empty'] = max(0, $stats['empty']);

        // Accuracy Percentage
        $stats['accuracy'] = $stats['total_questions'] > 0
            ? round(($stats['correct'] / $stats['total_questions']) * 100, 1)
            : 0;

        // Subtest Breakdown
        $breakdown = $subtests->map(function ($subtest) use ($answers) {
            $validQuestions = $subtest->questions->sortBy('pivot.order')->take($subtest->total_questions);
            $subtestQuestionIds = $validQuestions->pluck('id');
            $subtestAnswers = $answers->whereIn('question_id', $subtestQuestionIds);

            $correct = $subtestAnswers->where('is_correct', true)->count();
            $total = $validQuestions->count();

            return [
                'id' => $subtest->id,
                'title' => $subtest->title ?: ($subtest->subject->name ?? 'Subtest'),
                'total' => $total,
                'correct' => $correct,
                'score' => $total > 0 ? round(($correct / $total) * 100, 1) : 0,
            ];
        });

        // Time Spent
        $duration = max(1, (int) $examResult->started_at->diffInMinutes($examResult->finished_at ?: now()));
        $totalScore = $examResult->total_score;

        return view('admin.exam-sessions.student-results', compact('examSession', 'examResult', 'session', 'package', 'stats', 'breakdown', 'duration', 'totalScore'));
    }

    public function studentExplanation(ExamSession $examSession, \App\Models\ExamResult $examResult)
    {
        if ($examResult->exam_session_id !== $examSession->id) {
            abort(404);
        }

        $session = $examSession->load('examPackage.subtests.questions.options');
        $package = $session->examPackage;

        $subtests = $package->subtests->sortBy('order');
        $allQuestions = collect();
        foreach ($subtests as $subtest) {
            $subQuestions = $subtest->questions->sortBy('pivot.order')
                ->take($subtest->total_questions)
                ->values()
                ->map(function ($q) use ($subtest) {
                    $q->subtest_id = $subtest->id;
                    $q->subtest_title = $subtest->title ?: ($subtest->subject->name ?? 'Subtest');
                    return $q;
                });
            $allQuestions = $allQuestions->concat($subQuestions);
        }

        // Fetch user's answers
        $userAnswers = \App\Models\StudentAnswer::where('exam_result_id', $examResult->id)
            ->get()
            ->keyBy('question_id');

        return view('admin.exam-sessions.student-explanation', compact('examSession', 'examResult', 'session', 'package', 'allQuestions', 'userAnswers', 'subtests'));
    }
}
