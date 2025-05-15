<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
        <h5>Detail Pegawai</h5>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <td width=10%>Nama Pegawai</td>
                <td width=1%>:</td>
                <td>{{ $users->name }}</td>
            </tr>
            <tr>
                <td>Email Pegawai</td>
                <td>:</td>
                <td>{{ $users->email }}</td>
            </tr>
        </table>
        <hr>
        <div class="table-responsive text-nowrap">
            <table class="table" id="dataTable">
                <thead class="table-bordered">
                    <tr>
                        <th width=10%>Tanggal</th>
                        <th width=10%>Presensi Masuk</th>
                        <th width=10%>Presensi Keluar</th>
                        <th width=1%>Detail</th>
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
                                <td class="text-center"><span class="badge bg-label-danger">LIBUR</span></td>
                                <td class="text-center"><span class="badge bg-label-danger">LIBUR</span></td>
                                <td class="text-center text-danger"><i class="bx bx-x"></i></td>
                                <td>&nbsp;</td>
                            @elseif ($isHoliday)
                                <td class="text-center"><span class="badge bg-label-warning">PH</span></td>
                                <td class="text-center"><span class="badge bg-label-warning">PH</span></td>
                                <td class="text-center text-danger"><i class="bx bx-x"></i></td>
                                <td>&nbsp;</td>
                            @else
                                @if ($absence)
                                    @php
                                        // Get the first letter of the absent type name
                                        $absentTypeSymbol = strtoupper(substr($absence->master->name, 0, 1));
                                    @endphp
                                    <td class="text-center">
                                        <span class="badge bg-label-dark me-1">{{ $absentTypeSymbol }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-label-dark me-1">{{ $absentTypeSymbol }}</span>
                                    </td>
                                    <td class="text-center text-danger"><i class="bx bx-x"></i></td>
                                    <td>{{$absence->master->name}}</td>
                                @elseif ($attendance)

                                    @if(isset($attendance['is_late']) && $attendance['is_late'])
                                    <td class="text-center text-danger">{{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i:s') }}</td>
                                    <td class="text-center text-danger">{{ \Carbon\Carbon::parse($attendance->time_out)->format('H:i:s') }}</td>
                                    @else
                                    <td class="text-center">{{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i:s') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($attendance->time_out)->format('H:i:s') }}</td>
                                    @endif
                                    
                                    <td class="text-center">
                                        <button type="button" class="btn p-0 attendance-validate"
                                            data-bs-toggle="modal" data-bs-target="#modalCenter"
                                            data-apprin="{{ $attendance->approved }}"
                                            data-approut="{{ $attendance->approved_out }}"
                                            data-photo-in="{{ Storage::disk('assets')->url($attendance->photo_path) }}"
                                            data-photo-out="{{ $attendance->photo_path_out ? Storage::disk('assets')->url($attendance->photo_path_out) : '' }}"
                                            data-attendance-in="{{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i:s') }}"
                                            data-attendance-out="{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i:s') : 'Belum Presensi Keluar' }}">
                                            <i class='bx bx-show text-primary'></i>
                                        </button>
                                    </td>
                                    <td>{{ isset($attendance['note']) && $attendance['note'] ? ($attendance['note']) : '' }}</td>
                                @else
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center text-danger"><i class="bx bx-x"></i></td>
                                    <td class="text-center"></td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modalCenter" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header mb-0">
                <h5 class="modal-title" id="modalCenterTitle">Detail Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr>
            <div class="modal-body mt-0">
                <div class="row g-6">
                    <img src="{{ asset('assets/img/no-photo.jpg') }}" alt="Employee Photo" class="img-thumbnail mb-2"
                        style="max-width: 100%; height: auto;" id="photoIn">
                    <div class="form-group">
                        <label for="attendanceIn">Jam Masuk</label>
                        <input type="text" class="form-control" id="attendanceIn" value="" readonly>
                    </div>
                </div>
                <br>
                <div class="row g-6">
                    <div class="form-group">
                        <label for="attendanceIn">Status Validasi : </label>
                        <span id="status-in"></span>
                    </div>
                </div>
                <hr>
                <div class="row g-6">
                    <img src="{{ asset('assets/img/no-photo.jpg') }}" alt="Employee Photo" class="img-thumbnail mb-2"
                        style="max-width: 100%; height: auto;" id="photoOut">
                    <div class="form-group">
                        <label for="attendanceIn">Jam Keluar</label>
                        <input type="text" class="form-control" id="attendanceOut" value="" readonly>
                    </div>
                </div>
                <br>
                <div class="row g-6">
                    <div class="form-group">
                        <label for="attendanceIn">Status Validasi : </label>
                        <span id="status-out"></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
