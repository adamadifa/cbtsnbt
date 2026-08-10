<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExamResultsExport implements FromView, ShouldAutoSize
{
    protected $results;
    protected $matrixSubtests;

    public function __construct($results, $matrixSubtests)
    {
        $this->results = $results;
        $this->matrixSubtests = $matrixSubtests;
    }

    public function view(): View
    {
        return view('admin.exam-sessions.excel-results', [
            'results' => $this->results,
            'matrixSubtests' => $this->matrixSubtests
        ]);
    }
}
