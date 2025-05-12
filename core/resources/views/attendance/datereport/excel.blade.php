<!DOCTYPE html>
<html>

<head>
</head>

<body>
    <h1>Periode {{ \Carbon\Carbon::createFromFormat('Y-m', $title)->translatedFormat('F Y') }}</h1>
    <table>
        <thead>
            <tr>
                <th rowspan="3" style="vertical-align: middle; text-align:center;">Nama</th>
                <th colspan="{{ count($dates) }}" style="text-align:center;">Hari/Tanggal</th>
            </tr>
            <tr>
                @foreach ($dates as $index => $date)
                    <th>{{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($dates as $index => $date)
                    <th>{{ \Carbon\Carbon::parse($date)->format('d') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    @foreach ($dates as $date)
                        @php
                            $formattedDate = \Carbon\Carbon::parse($date)->format('Y-m-d');
                            $attendanceDate = \Carbon\Carbon::parse($date);
                            $isWeekend = $attendanceDate->isSaturday() || $attendanceDate->isSunday();
                            $isHoliday = in_array($formattedDate, $holidays);
                            if ($isWeekend) {
                                echo '<td style="background-color: #dc3545; color: white;">OFF</td>';
                                continue;
                            }
                            if ($isHoliday) {
                                echo '<td style="background-color: #ffd700; ">PH</td>';
                                continue;
                            }
                            $attendance = $user->attendances->first(function ($att) use ($formattedDate) {
                                return \Carbon\Carbon::parse($att->created_at)->format('Y-m-d') === $formattedDate;
                            });
                            $absence = $user->absents->first(function ($abs) use ($formattedDate) {
                                return \Carbon\Carbon::parse($abs->start_date)->format('Y-m-d') <= $formattedDate &&
                                    \Carbon\Carbon::parse($abs->end_date)->format('Y-m-d') >= $formattedDate;
                            });
                        @endphp
                        @if ($absence)
                            @php
                                // Get the first letter of the absent type name
                                $absentTypeSymbol = strtoupper(substr($absence->master->name, 0, 1));
                            @endphp
                            <td>
                                {{ $absentTypeSymbol }}
                            </td>
                        @elseif ($attendance)
                            @if ($attendance->approved == 2 && $attendance->approved_out == 2)
                                <td>V</td>
                            @elseif($attendance->approved == 1 && $attendance->approved_out == 1)
                                <td>i</td>
                            @elseif($attendance->time_out == null)
                                <td>!</td>
                            @else
                                <td>X</td>
                            @endif
                        @else
                            <td>X</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Keterangan :</p>
    <p>V : Hadir</p>
    <p>! : Belum Presensi Keluar</p>
    <p>X : Tidak Hadir</p>
    <p>i : Menunggu Validasi</p>
    @foreach ($masters as $master)
        <p>{{ strtoupper(substr($master->name, 0, 1)) }} : {{ $master->name }}</p>
    @endforeach
</body>

</html>
