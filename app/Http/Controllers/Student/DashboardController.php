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

    public function getCampusesList(Request $request)
    {
        $search = $request->query('q');

        // Cache lists depending on search query for 1 hour (3600 seconds)
        $cacheKey = 'campuses_list_' . md5($search);

        $campuses = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($search) {
            $query = CampusProdi::select('campus_name')->distinct();

            if (!empty($search)) {
                $query->where('campus_name', 'like', '%' . $search . '%');
            }

            return $query->orderBy('campus_name')
                ->limit(30) // Batasi maksimal 30 hasil agar load cepat
                ->pluck('campus_name');
        });

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

        $campus = $request->query('campus');
        $cacheKey = 'prodis_list_' . md5($campus);

        $prodis = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($campus) {
            return CampusProdi::where('campus_name', $campus)
                ->select('id', 'prodi_name', 'jenjang')
                ->orderBy('prodi_name')
                ->get();
        });

        return response()->json([
            'success' => true,
            'prodis' => $prodis
        ]);
    }
}
