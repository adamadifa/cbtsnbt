<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Jenssegers\Agent\Agent;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        
        // Fetch active sessions
        $sessions = ExamSession::with('examPackage')
            ->where('is_active', true)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->whereDoesntHave('results', function($q) {
                $q->where('user_id', Auth::id())->where('status', 'completed');
            })
            ->get()
            ->map(function($session) {
                // Check if there is an in-progress session
                $session->in_progress_result = $session->results()
                    ->where('user_id', Auth::id())
                    ->where('status', 'in_progress')
                    ->first();
                return $session;
            });

        // Fetch completed sessions
        $completedResults = \App\Models\ExamResult::with(['examSession', 'examSession.examPackage'])
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->latest('finished_at')
            ->get();

        $agent = new Agent();
        if ($agent->isMobile() || $agent->isTablet()) {
            return view('student.dashboard-mobile', compact('sessions', 'completedResults'));
        }

        return view('student.dashboard', compact('sessions', 'completedResults'));
    }
}
