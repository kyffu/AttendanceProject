<div class="table-responsive text-nowrap">
    <table class="table" id="dataTable">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $attendance)
                @php
                    $aDate = \Carbon\Carbon::parse($attendance['date']);
                    $fDate = $aDate->format('Y-m-d');
                    $isWeekend =$aDate->isSaturday() || $aDate->isSunday();
                    $isHoliday = in_array($fDate, $holidays);
                @endphp
                @if ($isWeekend || $isHoliday)
                    <tr class="bg-label-danger">
                        <td class="text-danger">{{ \Carbon\Carbon::parse($attendance['date'])->format('d-m-y') }}</td>
                        <td class="text-danger">N/A</td>
                        <td class="text-danger">N/A</td>
                        <td class="text-danger">LIBUR</td>
                    </tr>
                @else
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($attendance['date'])->format('d-m-y') }}</td>
                        @if(isset($attendance['is_late']) && $attendance['is_late'])
                        <td class="text-danger"> {{ isset($attendance['time_in']) ? \Carbon\Carbon::parse($attendance['time_in'])->format('H:i:s') : 'N/A' }}</td>
                        <td class="text-danger"> {{ isset($attendance['time_out']) ? \Carbon\Carbon::parse($attendance['time_out'])->format('H:i:s') : 'N/A' }}</td>
                        @else
                        <td> {{ isset($attendance['time_in']) ? \Carbon\Carbon::parse($attendance['time_in'])->format('H:i:s') : 'N/A' }}</td>
                        <td> {{ isset($attendance['time_out']) ? \Carbon\Carbon::parse($attendance['time_out'])->format('H:i:s') : 'N/A' }}</td>
                        @endif
                        <td>{{ $attendance['status'] }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
