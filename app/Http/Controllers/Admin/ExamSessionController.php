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
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean'
        ]);

        try {
            DB::beginTransaction();

            $session = ExamSession::create([
                'exam_package_id' => $request->exam_package_id,
                'title' => $request->title,
                'token' => strtoupper($request->token ?? Str::random(6)),
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

    public function show(ExamSession $examSession)
    {
        $examSession->load(['examPackage.subtests.subject', 'results.user']);
        
        $results = \App\Models\ExamResult::with(['user', 'answers', 'violations'])
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
                // Get pre-calculated points from StudentAnswer for these questions across all completed results
                $totalPoints = \App\Models\StudentAnswer::whereIn('exam_result_id', $completedResults->pluck('id'))
                    ->whereIn('question_id', $subtestQuestionIds)
                    ->sum('points');
                
                $avgCorrect = $totalPoints / $completedResults->count();
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

        // Assume total_score is a percentage or raw points. Adjust logic if needed.
        // Let's normalize it to 100 for distribution if we know the total questions.
        $totalPossible = $subtests->sum('total_questions');
        
        foreach ($completedResults as $result) {
            $percentage = $totalPossible > 0 ? ($result->total_score / $totalPossible) * 100 : 0;
            
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

    public function exportExcel(ExamSession $examSession)
    {
        $results = \App\Models\ExamResult::with(['user', 'answers', 'violations'])
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
        $results = \App\Models\ExamResult::with(['user', 'violations'])
            ->where('exam_session_id', $examSession->id)
            ->orderByDesc('total_score')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exam-sessions.pdf-results', [
            'session' => $examSession,
            'results' => $results
        ]);

        return $pdf->download('hasil_ujian_' . Str::slug($examSession->title) . '.pdf');
    }
}
