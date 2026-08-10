<table>
    <thead>
        <!-- Row 1: Headers & Subtest Names -->
        <tr>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle;">Nama Peserta</th>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle;">Email</th>
            <th rowspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: center; vertical-align: middle;">Sekolah</th>
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
