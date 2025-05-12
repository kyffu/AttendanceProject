<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
        <h5>Periode {{ \Carbon\Carbon::createFromFormat('Y-m', $title)->translatedFormat('F Y') }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table" id="dataTable">
                <thead class="table-bordered">
                    <tr>
                        <th rowspan="3" class="text-center" style="vertical-align: middle;">Nama</th>
                        <th colspan="{{ count($dates) }}" class="text-center">Hari/Tanggal</th>
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
                                        echo '<td class="bg-danger text-white p-0 text-center">OFF</td>';
                                        continue;
                                    }
                                    if ($isHoliday) {
                                        echo '<td class="bg-warning text-white p-0 text-center border">PH</td>';
                                        continue;
                                    }
                                    $attendance = $user->attendances->first(function ($att) use ($formattedDate) {
                                        return \Carbon\Carbon::parse($att->created_at)->format('Y-m-d') ===
                                            $formattedDate;
                                    });
                                    $absence = $user->absents->first(function ($abs) use ($formattedDate) {
                                        return \Carbon\Carbon::parse($abs->start_date)->format('Y-m-d') <=
                                            $formattedDate &&
                                            \Carbon\Carbon::parse($abs->end_date)->format('Y-m-d') >= $formattedDate;
                                    });
                                    
                                @endphp
                                @if ($absence)
                                    @php
                                        // Get the first letter of the absent type name
                                        $absentTypeSymbol = strtoupper(substr($absence->master->name, 0, 1));
                                    @endphp
                                    <td>
                                        <span class="badge bg-label-dark me-1">{{ $absentTypeSymbol }}</span>
                                    </td>
                                @elseif ($attendance)
                                    @if ($attendance->approved == 2 && $attendance->approved_out == 2)
                                        <td class="text-success"><i
                                                class="bx bx-check"></i></td>
                                    @elseif($attendance->approved == 1 && $attendance->approved_out == 1)
                                        <td class="text-info"><i
                                                class="bx bx-info-circle"></i></td>
                                    @elseif($attendance->time_out == null)
                                        <td class="text-warning"><i
                                                class="bx bx-error"></i></td>
                                    @else
                                        <td class="text-danger"><i
                                                class="bx bx-x"></i></td>
                                    @endif
                                @else
                                    <td class="text-danger"><i
                                            class="bx bx-x"></i></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        <p>Keterangan :</p>
        <p><span class="text-success"><i class="bx bx-check"></i></span>: Hadir</p>
        <p><span class="text-warning"><i class="bx bx-error"></i></span>: Belum Presensi Keluar</p>
        <p><span class="text-danger"><i class="bx bx-x"></i></span>: Tidak Hadir</p>
        <p><span class="text-info"><i class="bx bx-info-circle"></i></span>: Menunggu Validasi</p>
        @foreach ($masters as $master)
            <p><span class="badge bg-label-dark me-1">{{ strtoupper(substr($master->name, 0, 1)) }}</span>: {{$master->name}}</p>
        @endforeach
    </div>
</div>
