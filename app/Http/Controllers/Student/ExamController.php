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

            $startTime = now();
            $result = ExamResult::create([
                'user_id' => Auth::id(),
                'exam_session_id' => $session->id,
                'started_at' => $startTime,
                'status' => 'in_progress',
                'metadata' => [
                    'current_subtest_id' => $firstSubtest->id,
                    // Gunakan toIso8601String() agar JavaScript new Date() membaca timezone +07:00 dengan benar
                    // Format: "2026-11-05T20:30:00+07:00" — mencegah browser salah interpret sebagai UTC
                    'subtest_end_time' => $startTime->copy()->addMinutes($firstSubtest->duration_minutes)->toIso8601String(),
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
        if ($examResult->user_id !== Auth::id())
            abort(403);
        if ($examResult->status !== 'in_progress')
            return redirect()->route('dashboard');

        $session = $examResult->examSession()->with('examPackage.subtests')->first();

        // CATATAN: fallback ini hanya aktif jika metadata benar-benar null di DB.
        // Jangan reset subtest_end_time di sini — siswa yang reload/reconnect harus
        // tetap menggunakan end_time yang sudah tersimpan, bukan mendapat waktu baru.
        $metadata = $examResult->metadata;
        if (empty($metadata) || !isset($metadata['subtest_end_time'])) {
            $firstSubtest = $session->examPackage->subtests->sortBy('order')->first();
            $metadata = [
                'current_subtest_id' => $firstSubtest->id ?? null,
                'subtest_end_time' => now()->addMinutes($firstSubtest->duration_minutes ?? 0)->toIso8601String(),
                'completed_subtests' => []
            ];
            // Simpan metadata yang baru dibuat agar tidak ter-reset lagi pada reload berikutnya
            $examResult->update(['metadata' => $metadata]);
        }

        $currentSubtestId = $metadata['current_subtest_id'];

        $currentSubtest = $session->examPackage->subtests->find($currentSubtestId)
            ?? $session->examPackage->subtests->sortBy('order')->first();


        // Load questions with options ONLY for the current subtest (not all subtests)
        if ($currentSubtest) {
            $currentSubtest->load('questions.options');
        }

        // Filter questions for the current subtest from the EAGER LOADED collection
        $questions = $currentSubtest
            ? $currentSubtest->questions->sortBy('pivot.order')->take($currentSubtest->total_questions)->values()
            : collect();

        $allSubtests = $session->examPackage->subtests->sortBy('order');
        $totalExamQuestions = $allSubtests->sum('total_questions');

        // Fetch user's answers (all types)
        $userAnswers = StudentAnswer::where('exam_result_id', $examResult->id)
            ->get()
            ->keyBy('question_id')
            ->map(function ($ans) {
                return [
                    'option_id' => $ans->option_id,
                    'option_ids' => $ans->option_ids ?? [],
                    'matching_answers' => $ans->matching_answers ?? (object)[],
                    'essay_answer' => $ans->essay_answer ?? '',
                ];
            })
            ->toArray();

        $nextSubtestDelay = (int) \App\Models\Setting::getValue('next_subtest_delay', 10);

        $agent = new \Jenssegers\Agent\Agent();
        if ($agent->isMobile() || $agent->isTablet()) {
            return view('student.exam.show-mobile', compact('examResult', 'session', 'currentSubtest', 'questions', 'allSubtests', 'totalExamQuestions', 'userAnswers', 'metadata', 'nextSubtestDelay'));
        }

        return view('student.exam.show', compact('examResult', 'session', 'currentSubtest', 'questions', 'allSubtests', 'totalExamQuestions', 'userAnswers', 'metadata', 'nextSubtestDelay'));
    }

    public function saveAnswer(Request $request, ExamResult $examResult)
    {
        if ($examResult->user_id !== Auth::id() || $examResult->status !== 'in_progress') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'question_id' => 'required|integer',
            'option_id' => 'nullable|integer',
            'option_ids' => 'nullable|array',
            'matching_answers' => 'nullable|array',
            'essay_answer' => 'nullable|string|max:5000', // BUG-04: batasi panjang essay
        ]);

        $question = \App\Models\Question::with('options')->findOrFail($request->question_id);

        // BUG-01: Validasi bahwa soal ini memang bagian dari subtest yang sedang aktif
        $metadata = $examResult->metadata;
        $currentSubtestId = $metadata['current_subtest_id'] ?? null;
        $questionBelongsToCurrentSubtest = $question->subtests()
            ->where('exam_subtests.id', $currentSubtestId)
            ->exists();
        if (!$questionBelongsToCurrentSubtest) {
            return response()->json(['success' => false, 'message' => 'Soal tidak valid untuk subtest ini.'], 422);
        }
        $isCorrect = false;
        $points = 0;

        // Logic based on question type
        if ($question->type === 'pilihan_ganda') {
            $option = $request->option_id ? $question->options->find($request->option_id) : null;
            $isCorrect = $option ? $option->is_correct : false;
        } elseif ($question->type === 'pilihan_ganda_kompleks') {
            $selectedIds = array_map('intval', $request->option_ids ?? []);
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
        } elseif ($question->type === 'benar_salah') {
            $answers = $request->matching_answers ?? [];
            $isCorrect = true;
            
            if (count($answers) !== $question->options->count()) {
                $isCorrect = false;
            } else {
                foreach ($question->options as $option) {
                    $submittedVal = $answers[$option->id] ?? null;
                    $correctVal = $option->is_correct ? 'benar' : 'salah';
                    if ($submittedVal !== $correctVal) {
                        $isCorrect = false;
                        break;
                    }
                }
            }
        } elseif ($question->type === 'isian_singkat') {
            $submittedVal = trim(strtolower($request->essay_answer ?? ''));
            $isCorrect = false;
            foreach ($question->options as $option) {
                $correctVal = trim(strtolower($option->content));
                if ($submittedVal === $correctVal) {
                    $isCorrect = true;
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

        return response()->json([
            'success' => true,
            'message' => 'Jawaban tersimpan',
            'is_answered' => !empty($request->option_id) || !empty($request->option_ids) || !empty($request->matching_answers) || !empty($request->essay_answer)
        ]);
    }

    public function nextSubtest(ExamResult $examResult)
    {
        if ($examResult->user_id !== Auth::id() || $examResult->status !== 'in_progress')
            abort(403);

        // Duplicate submission guard menggunakan atomic cache lock
        // Mencegah double-submit dari double-click atau koneksi lambat
        $lockKey = 'next_subtest_lock_' . $examResult->id;
        if (!\Illuminate\Support\Facades\Cache::add($lockKey, true, 5)) {
            // Request duplikat dalam 5 detik — abaikan, redirect ke halaman yang sama
            return redirect()->route('student.exam.show', $examResult);
        }

        $metadata = $examResult->metadata;
        $session = $examResult->examSession()->with('examPackage.subtests')->first();
        $subtests = $session->examPackage->subtests->sortBy('order')->values();

        $currentIdx = $subtests->search(fn($s) => $s->id == $metadata['current_subtest_id']);

        // Guard: jika current_subtest_id tidak ditemukan (data corrupt), jangan loncat subtest.
        // Kembalikan siswa ke halaman ujian agar tetap di subtest yang seharusnya.
        if ($currentIdx === false) {
            \Log::warning("nextSubtest: current_subtest_id={$metadata['current_subtest_id']} tidak ditemukan untuk examResult={$examResult->id}. Dibatalkan.");
            return redirect()->route('student.exam.show', $examResult);
        }

        // Mark current as completed
        $completed = $metadata['completed_subtests'] ?? [];
        if (!in_array($metadata['current_subtest_id'], $completed)) {
            $completed[] = $metadata['current_subtest_id'];
        }

        $nextSubtest = $subtests->get($currentIdx + 1);

        if ($nextSubtest) {
            $metadata['current_subtest_id'] = $nextSubtest->id;
            $metadata['subtest_end_time'] = now()->addMinutes($nextSubtest->duration_minutes)->toIso8601String();
            $metadata['completed_subtests'] = $completed;
            $examResult->update(['metadata' => $metadata]);

            return redirect()->route('student.exam.show', $examResult);
        }

        // No more subtests, finish exam
        return $this->finish($examResult);
    }


    public function finish(ExamResult $examResult)
    {
        if ($examResult->user_id !== Auth::id())
            abort(403);

        // BUG-03: Guard duplikat — jika sudah completed, langsung redirect ke hasil
        if ($examResult->status === 'completed') {
            return redirect()->route('student.exam.results', $examResult);
        }

        DB::transaction(function () use ($examResult) {
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
        if ($examResult->user_id !== Auth::id())
            abort(403);

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

        // Time Spent (Rounded to nearest minute, at least 1)
        $duration = max(1, (int) $examResult->started_at->diffInMinutes($examResult->finished_at ?: now()));

        // Total Score from DB
        $totalScore = $examResult->total_score;

        $agent = new \Jenssegers\Agent\Agent();
        if ($agent->isMobile() || $agent->isTablet()) {
            return view('student.exam.results-mobile', compact('examResult', 'session', 'package', 'stats', 'breakdown', 'duration', 'totalScore'));
        }

        return view('student.exam.results', compact('examResult', 'session', 'package', 'stats', 'breakdown', 'duration', 'totalScore'));
    }

    public function explanation(ExamResult $examResult)
    {
        if ($examResult->user_id !== Auth::id())
            abort(403);

        // Check if explanation is allowed
        $session = $examResult->examSession()->with('examPackage.subtests.questions.options')->first();
        $package = $session->examPackage;

        if (!$package->show_explanation) {
            return redirect()->route('student.exam.results', $examResult)->with('error', 'Pembahasan tidak tersedia untuk ujian ini.');
        }

        $subtests = $package->subtests->sortBy('order');
        $allQuestions = collect();
        foreach ($subtests as $subtest) {
            // Apply the same limit and order on the EAGER LOADED collection
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
        $userAnswers = StudentAnswer::where('exam_result_id', $examResult->id)
            ->get()
            ->keyBy('question_id');

        $agent = new \Jenssegers\Agent\Agent();
        if ($agent->isMobile() || $agent->isTablet()) {
            return view('student.exam.explanation-mobile', compact('examResult', 'session', 'package', 'allQuestions', 'userAnswers', 'subtests'));
        }

        return view('student.exam.explanation', compact('examResult', 'session', 'package', 'allQuestions', 'userAnswers', 'subtests'));
    }

    public function logViolation(Request $request, ExamResult $examResult)
    {
        // Fitur pelanggaran dinonaktifkan sementara untuk performa optimal
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
