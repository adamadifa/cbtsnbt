<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\ExamResult;
use App\Models\StudentAnswer;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\MonitorExamUpdated;

class ExamController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'exam_session_id' => 'required|exists:exam_sessions,id',
            'token' => 'required|string',
        ]);

        $session = ExamSession::findOrFail($request->exam_session_id);

        // Verify Token & Active Status
        if (!$session->is_active || strtoupper($request->token) !== strtoupper($session->token)) {
            return back()->withErrors(['token' => 'Token tidak valid atau sesi sudah tidak aktif.']);
        }

        // Verify Time
        $now = now();
        if ($now->lt($session->start_time) || $now->gt($session->end_time)) {
            return back()->withErrors(['token' => 'Sesi ujian belum dimulai atau sudah berakhir.']);
        }

        // Check for existing result
        $result = ExamResult::where('user_id', Auth::id())
            ->where('exam_session_id', $session->id)
            ->first();

        if ($result && $result->status === 'completed') {
            return redirect()->route('dashboard')->with('error', 'Kamu sudah menyelesaikan ujian ini.');
        }

        if (!$result) {
            $firstSubtest = $session->examPackage->subtests()->orderBy('order')->first();
            
            $result = ExamResult::create([
                'user_id' => Auth::id(),
                'exam_session_id' => $session->id,
                'started_at' => $now,
                'status' => 'in_progress',
                'metadata' => [
                    'current_subtest_id' => $firstSubtest->id,
                    'subtest_end_time' => $now->addMinutes($firstSubtest->duration_minutes)->toDateTimeString(),
                    'completed_subtests' => []
                ]
            ]);

            event(new MonitorExamUpdated($session->id));
        }

        return redirect()->route('student.exam.show', $result);
    }

    public function show(ExamResult $examResult)
    {
        // Security Check
        if ($examResult->user_id !== Auth::id()) abort(403);
        if ($examResult->status !== 'in_progress') return redirect()->route('dashboard');

        $session = $examResult->examSession()->with('examPackage.subtests.questions.options')->first();
        
        $metadata = $examResult->metadata ?? [
            'current_subtest_id' => $session->examPackage->subtests->first()->id ?? null,
            'subtest_end_time' => now()->addMinutes($session->examPackage->subtests->first()->duration_minutes ?? 0)->toDateTimeString(),
            'completed_subtests' => []
        ];

        $currentSubtestId = $metadata['current_subtest_id'];
        $currentSubtest = $session->examPackage->subtests->find($currentSubtestId);
        
        // Filter questions for the current subtest from the EAGER LOADED collection
        $questions = $currentSubtest 
            ? $currentSubtest->questions->sortBy('pivot.order')->take($currentSubtest->total_questions)->values()
            : collect();

        $allSubtests = $session->examPackage->subtests->sortBy('order');
        $totalExamQuestions = $allSubtests->sum('total_questions');

        // Fetch user's answers
        $userAnswers = StudentAnswer::where('exam_result_id', $examResult->id)
            ->pluck('option_id', 'question_id')
            ->toArray();

        return view('student.exam.show', compact('examResult', 'session', 'currentSubtest', 'questions', 'allSubtests', 'totalExamQuestions', 'userAnswers', 'metadata'));
    }

    public function saveAnswer(Request $request, ExamResult $examResult)
    {
        if ($examResult->user_id !== Auth::id() || $examResult->status !== 'in_progress') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_id' => 'nullable|integer',
            'option_ids' => 'nullable|array',
            'matching_answers' => 'nullable|array',
            'essay_answer' => 'nullable|string',
        ]);

        $question = \App\Models\Question::with('options')->findOrFail($request->question_id);
        $isCorrect = false;
        $points = 0;

        // Logic based on question type
        if ($question->type === 'pilihan_ganda') {
            $option = $request->option_id ? $question->options->find($request->option_id) : null;
            $isCorrect = $option ? $option->is_correct : false;
        } elseif ($question->type === 'pilihan_ganda_kompleks') {
            $selectedIds = $request->option_ids ?? [];
            $correctIds = $question->options->where('is_correct', true)->pluck('id')->toArray();
            
            // Correct if both sets match exactly
            sort($selectedIds);
            sort($correctIds);
            $isCorrect = ($selectedIds === $correctIds);
        } elseif ($question->type === 'menjodohkan') {
            $answers = $request->matching_answers ?? [];
            $isCorrect = true;
            foreach ($question->options as $option) {
                // In 'menjodohkan', label is Left, content is Right
                // Frontend sends matching_answers as {premise_id: match_id}
                if (!isset($answers[$option->id]) || $answers[$option->id] != $option->id) {
                    $isCorrect = false;
                    break;
                }
            }
        } elseif ($question->type === 'essai') {
            $isCorrect = false; // Manual grading
        }

        $points = $isCorrect ? $question->points : 0;

        $answer = StudentAnswer::updateOrCreate(
            ['exam_result_id' => $examResult->id, 'question_id' => $request->question_id],
            [
                'option_id' => $request->option_id,
                'option_ids' => $request->option_ids,
                'matching_answers' => $request->matching_answers,
                'essay_answer' => $request->essay_answer,
                'is_correct' => $isCorrect,
                'points' => $points
            ]
        );

        // Update live score
        $totalScore = StudentAnswer::where('exam_result_id', $examResult->id)->sum('points');
        $examResult->update(['total_score' => $totalScore]);

        // Broadcast to update admin dashboard live
        event(new MonitorExamUpdated($examResult->exam_session_id));

        return response()->json([
            'success' => true,
            'message' => 'Jawaban tersimpan',
            'is_answered' => !empty($request->option_id) || !empty($request->option_ids) || !empty($request->matching_answers) || !empty($request->essay_answer)
        ]);
    }

    public function nextSubtest(ExamResult $examResult)
    {
        if ($examResult->user_id !== Auth::id() || $examResult->status !== 'in_progress') abort(403);

        $metadata = $examResult->metadata;
        $session = $examResult->examSession()->with('examPackage.subtests')->first();
        $subtests = $session->examPackage->subtests->sortBy('order');

        $currentIdx = $subtests->search(fn($s) => $s->id == $metadata['current_subtest_id']);
        
        // Mark current as completed
        $completed = $metadata['completed_subtests'] ?? [];
        if (!in_array($metadata['current_subtest_id'], $completed)) {
            $completed[] = $metadata['current_subtest_id'];
        }

        $nextSubtest = $subtests->get($currentIdx + 1);

        if ($nextSubtest) {
            $metadata['current_subtest_id'] = $nextSubtest->id;
            $metadata['subtest_end_time'] = now()->addMinutes($nextSubtest->duration_minutes)->toDateTimeString();
            $metadata['completed_subtests'] = $completed;
            $examResult->update(['metadata' => $metadata]);

            return redirect()->route('student.exam.show', $examResult);
        }

        // No more subtests, finish exam
        return $this->finish($examResult);
    }

    public function finish(ExamResult $examResult)
    {
        if ($examResult->user_id !== Auth::id()) abort(403);

        DB::transaction(function() use ($examResult) {
            $totalScore = StudentAnswer::where('exam_result_id', $examResult->id)->sum('points');
            
            $examResult->update([
                'status' => 'completed',
                'finished_at' => now(),
                'total_score' => $totalScore
            ]);
        });

        event(new MonitorExamUpdated($examResult->exam_session_id));

        return redirect()->route('student.exam.results', $examResult)->with('success', 'Ujian selesai! Terima kasih telah mengikuti tryout.');
    }

    public function results(ExamResult $examResult)
    {
        if ($examResult->user_id !== Auth::id()) abort(403);
        
        // Ensure the exam is completed
        if ($examResult->status !== 'completed') {
            return redirect()->route('student.exam.show', $examResult);
        }

        $session = $examResult->examSession()->with('examPackage.subtests.questions.options')->first();
        $package = $session->examPackage;

        // If results are hidden, show a minimalist page or redirect
        if (!$package->show_result) {
            return view('student.exam.results_hidden', compact('examResult', 'session'));
        }

        $subtests = $package->subtests;
        $answers = StudentAnswer::where('exam_result_id', $examResult->id)->get();

        // Calculate Stats
        $stats = [
            'total_questions' => $subtests->sum(fn($s) => min($s->total_questions, $s->questions->count())),
            'answered' => $answers->count(),
            'correct' => $answers->where('is_correct', true)->count(),
            'wrong' => $answers->where('is_correct', false)->count(),
            'empty' => 0, // Calculated below
        ];
        $stats['empty'] = $stats['total_questions'] - $stats['answered'];
        $stats['empty'] = max(0, $stats['empty']); // Prevent negative if answered > total somehow

        // Accuracy Percentage
        $stats['accuracy'] = $stats['total_questions'] > 0 
            ? round(($stats['correct'] / $stats['total_questions']) * 100, 1) 
            : 0;

        // Subtest Breakdown
        $breakdown = $subtests->map(function($subtest) use ($answers) {
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

        // Time Spent (Rounded to nearest minute, at least 1)
        $duration = max(1, (int) $examResult->started_at->diffInMinutes($examResult->finished_at ?: now()));

        // Total Score from DB
        $totalScore = $examResult->total_score;

        return view('student.exam.results', compact('examResult', 'session', 'package', 'stats', 'breakdown', 'duration', 'totalScore'));
    }

    public function explanation(ExamResult $examResult)
    {
        if ($examResult->user_id !== Auth::id()) abort(403);
        
        // Check if explanation is allowed
        $session = $examResult->examSession()->with('examPackage.subtests.questions.options')->first();
        $package = $session->examPackage;

        if (!$package->show_explanation) {
            return redirect()->route('student.exam.results', $examResult)->with('error', 'Pembahasan tidak tersedia untuk ujian ini.');
        }

        // Fetch subtests that were actually attempted (completed or current)
        $metadata = $examResult->metadata ?? [];
        $attemptedSubtestIds = array_unique(array_merge(
            $metadata['completed_subtests'] ?? [],
            [$metadata['current_subtest_id'] ?? null]
        ));

        $subtests = $package->subtests->whereIn('id', $attemptedSubtestIds)->sortBy('order');
        $allQuestions = collect();
        foreach ($subtests as $subtest) {
            // Apply the same limit and order on the EAGER LOADED collection
            $subQuestions = $subtest->questions->sortBy('pivot.order')
                ->take($subtest->total_questions)
                ->values()
                ->map(function($q) use ($subtest) {
                    $q->subtest_id = $subtest->id; 
                    $q->subtest_title = $subtest->title ?: ($subtest->subject->name ?? 'Subtest');
                    return $q;
                });
            $allQuestions = $allQuestions->concat($subQuestions);
        }

        // Fetch user's answers
        $userAnswers = StudentAnswer::where('exam_result_id', $examResult->id)
            ->get()
            ->keyBy('question_id');

        return view('student.exam.explanation', compact('examResult', 'session', 'package', 'allQuestions', 'userAnswers', 'subtests'));
    }

    public function logViolation(Request $request, ExamResult $examResult)
    {
        if ($examResult->user_id !== Auth::id() || $examResult->status !== 'in_progress') {
            return response()->json(['success' => false], 403);
        }

        $request->validate([
            'type' => 'required|string|in:tab_switch,focus_lost',
        ]);

        $examResult->violations()->create([
            'type' => $request->type,
            'details' => $request->details,
        ]);

        // Broadcast to admin dashboard
        event(new \App\Events\MonitorExamUpdated($examResult->exam_session_id));

        return response()->json(['success' => true]);
    }

    public function downloadCertificate(ExamResult $examResult)
    {
        if ($examResult->user_id !== Auth::id() || $examResult->status !== 'completed') {
            abort(403);
        }

        $examResult->load(['user', 'examSession']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('student.exam.certificate', [
            'examResult' => $examResult
        ]);

        return $pdf->setPaper('a4', 'landscape')->download('sertifikat_' . \Illuminate\Support\Str::slug($examResult->examSession->title) . '.pdf');
    }
}
