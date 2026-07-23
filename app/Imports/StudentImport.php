<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class StudentImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $user = User::create([
            'name'     => $row['nama'],
            'email'    => $row['email'],
            'password' => Hash::make($row['password'] ?? '12345678'),
            'school'   => $row['sekolah'] ?? null,
        ]);

        $user->assignRole('student');

        return $user;
    }

    public function rules(): array
    {
        return [
            'nama'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|min:6',
        ];
    }
}
