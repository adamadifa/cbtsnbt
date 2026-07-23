<!DOCTYPE html>
<html>
<head>
    <title>Hasil Ujian - {{ $session->title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f8fafc; font-weight: bold; text-transform: uppercase; font-size: 10px; color: #64748b; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .badge-success { background-color: #dcfce7; color: #166534; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN HASIL UJIAN</h1>
        <p>{{ $session->title }}</p>
        <p>Paket: {{ $session->examPackage->title }} | Tanggal: {{ $session->start_time->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th>Nama Peserta</th>
                <th>Asal Sekolah</th>
                <th class="text-center">Status</th>
                <th class="text-center">Pelanggaran</th>
                <th class="text-right">Skor Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $result)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $result->user->name }}</strong><br>
                        <span style="font-size: 9px; color: #94a3b8;">{{ $result->user->email }}</span>
                    </td>
                    <td>{{ $result->user->school ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $result->status === 'completed' ? 'badge-success' : 'badge-warning' }}">
                            {{ $result->status === 'completed' ? 'Selesai' : 'Mengerjakan' }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($result->violations->count() > 0)
                            <span class="badge badge-danger">{{ $result->violations->count() }} Kali</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        <strong style="font-size: 14px; color: #4f46e5;">{{ $result->total_score }}</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
