<?php

namespace App\Imports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class SubjectImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Subject([
            'name'        => $row['nama'],
            'code'        => $row['kode'] ?? Str::slug($row['nama']),
            'component'   => $row['komponen'] ?? 'umum',
            'description' => $row['deskripsi'] ?? null,
            'icon'        => $row['icon'] ?? 'BookOpen',
            'color'       => $row['warna'] ?? 'indigo',
            'order'       => $row['urutan'] ?? 0,
            'is_active'   => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50|unique:subjects,code',
        ];
    }
}
