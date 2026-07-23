<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Imports\SubjectImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        $subjects = $query->orderBy('order')->latest()->paginate(10);

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'component' => 'required|string|max:50',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if (!$request->has('code') || empty($request->code)) {
            $validated['code'] = Str::slug($request->name);
        }

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'component' => 'required|string|max:50',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        // Check if subject has questions (to be implemented later)
        // For now, just delete
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new SubjectImport, $request->file('file'));
            return redirect()->route('admin.subjects.index')->with('success', 'Data Mata Pelajaran berhasil di-import.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal meng-import data: ' . $e->getMessage());
        }
    }
}
