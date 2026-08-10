<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\CampusProdi;
use App\Models\StudentTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $user = Auth::user();
        
        // Fetch student targets
        $targets = $user->targets()->with('campusProdi')->get();
        $mustSelectTargets = $targets->isEmpty();
        
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
        
        $viewData = compact('sessions', 'completedResults', 'targets', 'mustSelectTargets');
        
        if ($agent->isMobile() || $agent->isTablet()) {
            return view('student.dashboard-mobile', $viewData);
        }

        return view('student.dashboard', $viewData);
    }

    public function saveTargets(Request $request)
    {
        $request->validate([
            'targets' => 'required|array|min:1|max:4',
            'targets.*.campus_prodi_id' => 'required|exists:campus_prodis,id',
        ]);

        $user = Auth::user();

        // Clear existing targets
        $user->targets()->delete();

        // Save new targets
        foreach ($request->input('targets') as $index => $targetData) {
            StudentTarget::create([
                'user_id' => $user->id,
                'campus_prodi_id' => $targetData['campus_prodi_id'],
                'order' => $index + 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pilihan kampus tujuan berhasil disimpan.'
        ]);
    }

    public function getCampusesList()
    {
        $campuses = CampusProdi::select('campus_name')
            ->distinct()
            ->orderBy('campus_name')
            ->pluck('campus_name');

        return response()->json([
            'success' => true,
            'campuses' => $campuses
        ]);
    }

    public function getCampusProdisList(Request $request)
    {
        $request->validate([
            'campus' => 'required|string'
        ]);

        $prodis = CampusProdi::where('campus_name', $request->query('campus'))
            ->select('id', 'prodi_name', 'jenjang')
            ->orderBy('prodi_name')
            ->get();

        return response()->json([
            'success' => true,
            'prodis' => $prodis
        ]);
    }
}
