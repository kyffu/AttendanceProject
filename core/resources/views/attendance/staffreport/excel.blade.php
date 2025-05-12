<!DOCTYPE html>
<html>

<head>
</head>

<body>
    <table>
        <tr>
            <td colspan="3">Nama Pegawai : {{ $users->name }}</td>
        </tr>
        <tr>
            <td colspan="3">Email Pegawai : {{ $users->email }}</td>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Presensi Masuk</th>
                <th>Presensi Keluar</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dates as $date)
                @php
                    $formattedDate = \Carbon\Carbon::parse($date)->format('Y-m-d');
                    $attendanceDate = \Carbon\Carbon::parse($date);
                    $attendance = $users->attendances->first(function ($att) use ($formattedDate) {
                        return \Carbon\Carbon::parse($att->created_at)->format('Y-m-d') === $formattedDate;
                    });
                    $absence = $users->absents->first(function ($abs) use ($formattedDate) {
                        return \Carbon\Carbon::parse($abs->start_date)->format('Y-m-d') <= $formattedDate &&
                            \Carbon\Carbon::parse($abs->end_date)->format('Y-m-d') >= $formattedDate;
                    });
                    $isWeekend = $attendanceDate->isSaturday() || $attendanceDate->isSunday();
                    $isHoliday = in_array($formattedDate, $holidays);
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                    @if ($isWeekend)
                        <td style="text-align: center;"><span style="color: red">LIBUR</span></td>
                        <td style="text-align: center;"><span style="color: red">LIBUR</span></td>
                        <td style="text-align: center; color:red;">X</td>
                        <td>&nbsp;</td>
                    @elseif ($isHoliday)
                        <td style="text-align: center;"><span style="color: rgb(255, 217, 0)">PH</span></td>
                        <td style="text-align: center;"><span style="color: rgb(255, 217, 0)">PH</span></td>
                        <td style="text-align: center; color:red;">X</td>
                        <td>&nbsp;</td>
                    @else
                        @if ($absence)
                            @php
                                // Get the first letter of the absent type name
                                $absentTypeSymbol = strtoupper(substr($absence->master->name, 0, 1));
                            @endphp
                            <td style="text-align: center;">
                                {{ $absentTypeSymbol }}
                            </td>
                            <td style="text-align: center;">
                                {{ $absentTypeSymbol }}
                            </td>
                            <td>{{ $absence->master->name }}</td>
                        @elseif ($attendance)
                            <td>{{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i:s') }}</td>
                            <td>{{ \Carbon\Carbon::parse($attendance->time_out)->format('H:i:s') }}</td>
                            <td>&nbsp;</td>
                        @else
                            <td>-</td>
                            <td>-</td>
                            <td>&nbsp;</td>
                        @endif
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
