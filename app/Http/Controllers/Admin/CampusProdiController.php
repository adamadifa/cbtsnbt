<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampusProdi;
use App\Services\CampusProdiImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CampusProdiController extends Controller
{
    protected $importService;

    public function __construct(CampusProdiImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index(Request $request)
    {
        // Group by campus_name to show a list of unique campuses
        $query = CampusProdi::select('campus_name', DB::raw('count(prodi_name) as total_prodi'))
            ->groupBy('campus_name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('campus_name', 'like', "%{$search}%")
                  ->orWhereIn('campus_name', function($subQuery) use ($search) {
                      $subQuery->select('campus_name')
                               ->from('campus_prodis')
                               ->where('prodi_name', 'like', "%{$search}%");
                  });
            });
        }

        $records = $query->paginate(15)->withQueryString();

        return view('admin.campus-prodis.index', compact('records'));
    }

    public function getProdisByCampus(Request $request)
    {
        $request->validate([
            'campus' => 'required|string',
        ]);

        $campusName = $request->query('campus');
        
        $prodis = CampusProdi::where('campus_name', $campusName)
            ->select('id', 'prodi_name', 'jenjang')
            ->orderBy('prodi_name')
            ->get();

        return response()->json([
            'success' => true,
            'campus' => $campusName,
            'prodis' => $prodis,
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:15360',
        ]);

        $file = $request->file('file');
        $path = $file->storeAs('temp-imports', uniqid() . '.' . $file->getClientOriginalExtension());
        $absolutePath = storage_path('app/private/' . $path);
        if (!file_exists($absolutePath)) {
            $absolutePath = storage_path('app/' . $path);
        }

        try {
            $sheets = $this->importService->getSheetNames($absolutePath);
            return response()->json([
                'success' => true,
                'temp_path' => $path,
                'sheets' => $sheets,
            ]);
        } catch (\Exception $e) {
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
            return response()->json([
                'success' => false,
                'message' => 'Gagal membaca file: ' . $e->getMessage()
            ], 422);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'temp_path' => 'required|string',
            'sheet_name' => 'required|string',
        ]);

        $tempPath = $request->input('temp_path');
        $sheetName = $request->input('sheet_name');
        
        $absolutePath = storage_path('app/private/' . $tempPath);
        if (!file_exists($absolutePath)) {
            $absolutePath = storage_path('app/' . $tempPath);
        }

        if (!file_exists($absolutePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan di server. Silakan upload ulang.'
            ], 422);
        }

        try {
            $importedCount = $this->importService->importSheet($absolutePath, $sheetName);
            
            if (Storage::exists($tempPath)) {
                Storage::delete($tempPath);
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengimport {$importedCount} data dari sheet '{$sheetName}'."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses import: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyAll()
    {
        CampusProdi::truncate();
        return redirect()->route('admin.campus-prodis.index')->with('success', 'Semua data kampus & prodi berhasil dihapus.');
    }
}
