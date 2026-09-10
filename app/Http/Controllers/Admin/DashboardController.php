<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Question;
use App\Models\ExamSession;
use App\Models\ExamPackage;
use App\Models\ExamResult;
use App\Models\Subject;
use App\Models\StudentAnswer;
use App\Models\ExamViolation;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Basic Stats
        $totalStudents = User::role('siswa')->count();
        $totalQuestions = Question::count();
        $totalPackages = ExamPackage::count();
        $totalSubjects = Subject::count();
        
        $activeSessions = ExamSession::where(function($q) {
            $q->where('status', 'active')
              ->orWhere('is_active', true);
        })->count();

        $totalAttempts = ExamResult::count();
        $finishedAttempts = ExamResult::whereNotNull('finished_at')->count();
        $activeAttempts = ExamResult::whereNull('finished_at')->count();

        // Violations count
        $activityViolations = ActivityLog::whereIn('action', [
            'tab_switch', 
            'window_blur', 
            'fullscreen_exit', 
            'copy_attempt', 
            'right_click'
        ])->count();
        $tableViolations = ExamViolation::count();
        $totalViolations = $activityViolations + $tableViolations;

        // Passing Rate (attempts with total_score >= passing_score or 50)
        $passedCount = ExamResult::where('status', 'completed')
            ->where('total_score', '>=', 50)
            ->count();
        $passingRate = $finishedAttempts > 0 ? round(($passedCount / $finishedAttempts) * 100, 1) : 0;

        // Completion Rate
        $completionRate = $totalAttempts > 0 ? round(($finishedAttempts / $totalAttempts) * 100, 1) : ($totalStudents > 0 ? 0 : 100);

        // Average Duration in minutes
        $avgDurationMinutes = 0;
        $completedResults = ExamResult::whereNotNull('started_at')
            ->whereNotNull('finished_at')
            ->get();
        if ($completedResults->count() > 0) {
            $totalMinutes = $completedResults->reduce(function ($carry, $result) {
                return $carry + abs($result->finished_at->diffInMinutes($result->started_at));
            }, 0);
            $avgDurationMinutes = round($totalMinutes / $completedResults->count());
        }

        // 2. Student Distribution by School / Type
        $smaCount = User::role('siswa')->where('school', 'like', '%SMA%')->orWhere('school', 'like', '%MA%')->count();
        $smkCount = User::role('siswa')->where('school', 'like', '%SMK%')->count();
        $umumCount = max(0, $totalStudents - ($smaCount + $smkCount));

        $smaPercent = $totalStudents > 0 ? round(($smaCount / $totalStudents) * 100) : 0;
        $smkPercent = $totalStudents > 0 ? round(($smkCount / $totalStudents) * 100) : 0;
        $umumPercent = $totalStudents > 0 ? max(0, 100 - ($smaPercent + $smkPercent)) : 0;

        // 3. Weekly Activity Chart (Last 7 Days)
        $weeklyChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $date->locale('id')->isoFormat('ddd');
            $count = ExamResult::whereDate('created_at', $date->toDateString())->count();
            $weeklyChart[] = [
                'day' => $dayName,
                'date' => $date->format('d M'),
                'is_today' => $i === 0,
                'count' => $count
            ];
        }

        $maxWeeklyCount = max(array_column($weeklyChart, 'count'));

        // 4. Total Answers Submitted
        $totalAnswers = StudentAnswer::count();
        $recentAnswersCount = StudentAnswer::where('created_at', '>=', now()->subDays(15))->count();

        // 5. Latest Activity Logs / Results
        $latestActivities = [];
        
        // Fetch real exam results first
        $recentResults = ExamResult::with(['user', 'examSession.examPackage'])
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentResults as $res) {
            $latestActivities[] = [
                'id' => $res->id,
                'user_name' => $res->user->name ?? 'Siswa',
                'action' => $res->status == 'completed' ? 'Ujian Selesai' : 'Mengerjakan Ujian',
                'package_title' => $res->examSession->examPackage->title ?? ($res->examSession->title ?? 'Tryout'),
                'time_ago' => $res->created_at->diffForHumans(null, true),
                'status' => $res->status == 'completed' ? 'Solved' : 'In-progress',
                'badge_class' => $res->status == 'completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600',
            ];
        }

        // If activity logs exist, merge
        $dbLogs = ActivityLog::with('user')->latest()->take(5)->get();
        foreach ($dbLogs as $log) {
            $latestActivities[] = [
                'id' => $log->id,
                'user_name' => $log->user->name ?? 'User',
                'action' => str_replace('_', ' ', $log->action),
                'package_title' => 'Sesi #' . $log->id,
                'time_ago' => $log->created_at->diffForHumans(null, true),
                'status' => in_array($log->action, ['exam_finished', 'exam_completed']) ? 'Solved' : 'Pending',
                'badge_class' => in_array($log->action, ['exam_finished', 'exam_completed']) ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600',
            ];
        }

        $latestActivities = array_slice($latestActivities, 0, 4);

        // 6. Active Students right now
        $activeStudents = User::role('siswa')
            ->whereHas('examResults', function($q) {
                $q->whereNull('finished_at');
            })
            ->with(['examResults' => function($q) {
                $q->whereNull('finished_at')->latest();
            }, 'examResults.examSession.examPackage'])
            ->take(5)
            ->get();

        $stats = [
            'total_students' => $totalStudents,
            'total_questions' => $totalQuestions,
            'total_packages' => $totalPackages,
            'total_subjects' => $totalSubjects,
            'active_sessions' => $activeSessions,
            'total_attempts' => $totalAttempts,
            'finished_attempts' => $finishedAttempts,
            'active_attempts' => $activeAttempts,
            'total_violations' => $totalViolations,
            'passing_rate' => $passingRate,
            'completion_rate' => $completionRate,
            'avg_duration_minutes' => $avgDurationMinutes,
            'sma_count' => $smaCount,
            'smk_count' => $smkCount,
            'umum_count' => $umumCount,
            'sma_percent' => $smaPercent,
            'smk_percent' => $smkPercent,
            'umum_percent' => $umumPercent,
            'total_answers' => $totalAnswers,
            'recent_answers_count' => $recentAnswersCount,
        ];

        return view('admin.dashboard', compact('stats', 'weeklyChart', 'maxWeeklyCount', 'latestActivities', 'activeStudents'));
    }
}
