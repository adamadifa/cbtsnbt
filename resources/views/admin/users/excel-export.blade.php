<table>
    <thead>
        <tr>
            <th style="background-color: #f97316; color: #ffffff; font-weight: bold; text-align: center;">No</th>
            <th style="background-color: #f97316; color: #ffffff; font-weight: bold; text-align: center;">Nama Lengkap</th>
            <th style="background-color: #f97316; color: #ffffff; font-weight: bold; text-align: center;">Email</th>
            <th style="background-color: #f97316; color: #ffffff; font-weight: bold; text-align: center;">Peran (Role)</th>
            <th style="background-color: #f97316; color: #ffffff; font-weight: bold; text-align: center;">Asal Sekolah</th>
            <th style="background-color: #f97316; color: #ffffff; font-weight: bold; text-align: center;">Tanggal Registrasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $index => $user)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td style="text-align: center;">{{ ucfirst(str_replace('_', ' ', $user->roles->pluck('name')->first() ?? 'Siswa')) }}</td>
                <td>{{ $user->school ?? '-' }}</td>
                <td style="text-align: center;">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
