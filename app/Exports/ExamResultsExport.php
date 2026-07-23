<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExamResultsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function collection()
    {
        return $this->results;
    }

    public function headings(): array
    {
        return [
            'Nama Peserta',
            'Email',
            'Sekolah',
            'Status',
            'Mulai',
            'Selesai',
            'Pelanggaran',
            'Total Skor'
        ];
    }

    public function map($result): array
    {
        return [
            $result->user->name,
            $result->user->email,
            $result->user->school ?? '-',
            $result->status === 'completed' ? 'Selesai' : 'Mengerjakan',
            $result->started_at->format('d/m/Y H:i'),
            $result->finished_at ? $result->finished_at->format('d/m/Y H:i') : '-',
            $result->violations->count(),
            $result->total_score
        ];
    }
}
