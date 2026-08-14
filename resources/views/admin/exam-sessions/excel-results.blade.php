<table>
    <thead>
        <!-- Row 1: Headers & Subtest Names -->
        <tr>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle;">Nama Peserta</th>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle;">Email</th>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle;">Sekolah</th>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle;">Pilihan 1</th>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle;">Pilihan 2</th>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle;">Pilihan 3</th>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle;">Pilihan 4</th>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle;">Total Skor</th>
            @foreach($matrixSubtests as $subtest)
                @if($subtest['questions']->count() > 0)
                    <th colspan="{{ $subtest['questions']->count() }}" style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #d9e1f2; vertical-align: middle;">
                        {{ $subtest['title'] }}
                    </th>
                @endif
            @endforeach
        </tr>
        <!-- Row 2: Question Numbers -->
        <tr>
            @foreach($matrixSubtests as $subtest)
                @foreach($subtest['questions'] as $index => $q)
                    <th style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #f2f2f2;">
                        {{ $index + 1 }}
                    </th>
                @endforeach
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($results as $result)
            @php
                $studentAnswers = $result->answers->keyBy('question_id');
            @endphp
            <tr>
                <td style="border: 1px solid #000000; vertical-align: middle;">{{ $result->user->name }}</td>
                <td style="border: 1px solid #000000; vertical-align: middle;">{{ $result->user->email }}</td>
                <td style="border: 1px solid #000000; vertical-align: middle;">{{ $result->user->school ?? '-' }}</td>
                @for($i = 1; $i <= 4; $i++)
                    @php
                        $target = $result->user->targets->where('order', $i)->first();
                    @endphp
                    <td style="border: 1px solid #000000; vertical-align: middle;">
                        {{ $target && $target->campusProdi ? $target->campusProdi->campus_name . ' - ' . $target->campusProdi->prodi_name : '-' }}
                    </td>
                @endfor
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold; vertical-align: middle;">{{ $result->total_score }}</td>
                @foreach($matrixSubtests as $subtest)
                    @foreach($subtest['questions'] as $q)
                        @php
                            $ans = $studentAnswers->get($q->id);
                            $isCorrect = $ans && $ans->is_correct;
                        @endphp
                        <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                            @if($ans)
                                {{ $isCorrect ? 1 : 0 }}
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
