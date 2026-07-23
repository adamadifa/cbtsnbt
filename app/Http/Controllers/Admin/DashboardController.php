<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Question;
use App\Models\ExamSession;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => User::role('siswa')->count(),
            'total_questions' => Question::count(),
            'active_sessions' => ExamSession::where('status', 'active')->count(),
            'total_violations' => ActivityLog::whereIn('action', [
                'tab_switch', 
                'window_blur', 
                'fullscreen_exit', 
                'copy_attempt', 
                'right_click'
            ])->count(),
        ];

        $latest_logs = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        $active_students = User::role('siswa')
            ->whereHas('examAttempts', function($q) {
                $q->whereNull('finished_at');
            })
            ->with(['examAttempts.examPackage'])
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latest_logs', 'active_students'));
    }
}
