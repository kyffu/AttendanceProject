@extends('layouts.app')
@section('title', 'Validasi Presensi')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Detail Presensi : {{ $name }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($attendance->approved == 2 && $attendance->approved_out == 2)
                                            <span class="badge bg-label-success">Tervalidasi</span>
                                        @else
                                            <span class="badge bg-label-danger">Belum Validasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($attendance->approved == 2 && $attendance->approved_out == 2)
                                            <span class="badge bg-success"><i class='bx bx-check'></i></span>
                                        @else
                                            <button type="button" class="btn btn-primary attendance-validate"
                                                data-bs-toggle="modal" data-bs-target="#modalCenter"
                                                data-attendance="{{ Crypt::encryptString($attendance->id) }}"
                                                data-apprin="{{ $attendance->approved }}"
                                                data-approut="{{ $attendance->approved_out }}"
                                                data-photo-in="{{ Storage::disk('assets')->url($attendance->photo_path) }}"
                                                data-photo-out="{{ $attendance->photo_path_out ? Storage::disk('assets')->url($attendance->photo_path_out) : '' }}"
                                                data-attendance-in="{{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i:s') }}"
                                                data-attendance-out="{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i:s') : 'Belum Presensi Keluar' }}">
                                                Validasi
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->
    </div>
    <div class="modal fade" id="modalCenter" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header mb-0">
                    <h5 class="modal-title" id="modalCenterTitle">Validasi Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr>
                <form action="{{ route('attendance.validate.do') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body mt-0">
                        <div class="row g-6">
                            <img src="{{asset('assets/img/no-photo.jpg')}}" alt="Employee Photo" class="img-thumbnail mb-2"
                                style="max-width: 100%; height: auto;" id="photoIn">
                            <div class="form-group">
                                <label for="attendanceIn">Jam Masuk</label>
                                <input type="text" class="form-control" id="attendanceIn" value="" readonly>
                            </div>
                        </div>
                        <br>
                        <div class="row g-6">
                            <img src="{{asset('assets/img/no-photo.jpg')}}" alt="Employee Photo" class="img-thumbnail mb-2"
                                style="max-width: 100%; height: auto;" id="photoOut">
                            <div class="form-group">
                                <label for="attendanceIn">Jam Keluar</label>
                                <input type="text" class="form-control" id="attendanceOut" value="" readonly>
                            </div>
                        </div>
                        <hr>
                        <p>Centang kotak dibawah ini untuk validasi presensi</p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ Crypt::encryptString('in') }}"
                                name="attendance[]" id="check-in">
                            <label class="form-check-label" for="defaultCheck3">
                                Presensi Masuk
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ Crypt::encryptString('out') }}"
                                name="attendance[]" id="check-out">
                            <label class="form-check-label" for="defaultCheck3">
                                Presensi Keluar
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="attid" id="attid" value="">
                        <button type="submit" class="btn btn-primary">Validasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.attendance-validate').on('click', function() {
                // Get data attributes from the clicked button
                var photoIn = $(this).data('photo-in');
                var photoOut = $(this).data('photo-out');
                var attendanceIn = $(this).data('attendance-in');
                var attendanceOut = $(this).data('attendance-out');
                var attid = $(this).data('attendance');
                var apprin = $(this).data('apprin');
                var approut = $(this).data('approut');

                // Set modal fields
                $('#photoIn').attr('src', photoIn);
                $('#photoOut').attr('src', photoOut);
                $('#attendanceIn').val(attendanceIn);
                $('#attendanceOut').val(attendanceOut);
                $('#attid').val(attid);
                if (apprin === 2) {
                    $('#check-in').prop('checked', true);
                } else {
                    $('#check-in').prop('checked', false);
                }
                if (approut === 2) {
                    $('#check-out').prop('checked', true);
                } else {
                    $('#check-out').prop('checked', false);
                }
            });
            $('#dataTable').DataTable();
        });
    </script>
@endpush
