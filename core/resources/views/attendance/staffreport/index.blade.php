@extends('layouts.app')
@section('title', 'Laporan Presensi')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card  mb-2">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header mb-0">
                <h5>Laporan Presensi Per Karyawan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                     
                    <div class="col-sm" id="col-staff">
                        <div class="form-group">
                            <strong>Nama Karyawan</strong>
                            <select name="staff" id="staff" class="form-select">
                                <option value="" selected disabled>Pilih Karyawan</option>
                                @foreach ($users as $user)
                                    <option value="{{ Crypt::encryptString($user->id) }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="staff" id="staff" value="{{Crypt::encryptString($user->id)}}">
                     
                    <div class="col-sm" id="col-month">
                        <div class="form-group">
                            <strong>Bulan</strong>
                            <select name="month" id="month" class="form-select">
                                <option value="" selected disabled>Pilih bulan</option>
                                @foreach ($months as $number => $month)
                                    @php
                                        $formatMonth = sprintf('%02d', $number);
                                    @endphp
                                    <option value="{{ Crypt::encryptString($formatMonth) }}">{{ $month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm" id="col-year">
                        <div class="form-group">
                            <strong>Tahun</strong>
                            <select name="year" id="year" class="form-select">
                                <option value="" selected disabled>Pilih tahun</option>
                                @foreach ($years as $year)
                                    <option value="{{ Crypt::encryptString($year) }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm" id="col-show">
                        <div class="form-group">
                            &nbsp;
                            <button class="btn btn-primary form-control" type="button" id="btn-show">Lihat</button>
                        </div>
                    </div>
                    <div class="col-sm" id="col-export" style="display: none;">
                        <div class="form-group">
                            &nbsp;
                            <button class="btn btn-info form-control" type="button" id="btn-export">Ekspor</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="report">

        </div>
    </div>
@stop
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#col-export').hide();
            $('#year').select2({
                theme: 'bootstrap-5'
            });
            $('#month').select2({
                theme: 'bootstrap-5'
            });
            $('#btn-show').click(function() {
                var year = $('#year').val();
                var month = $('#month').val();
                var staff = $('#staff').val();
                if (year !== null && month !== null) {
                    $('#col-spinner').show();
                    $('#report').empty();
                    var data = {
                        year: year,
                        month: month,
                        staff: staff,
                        method: 'GET'
                    };

                    $.ajax({
                        url: "{{ route('report.attendance.bystaff.get') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,
                        success: function(response) {
                            $('#col-spinner').hide();
                            $('#report').html(response.html);
                            $('#col-export').show();
                            $('.attendance-validate').on('click', function() {
                                $('#status-in').removeClass();
                                $('#status-out').removeClass();
                                var photoIn = $(this).data('photo-in');
                                var photoOut = $(this).data('photo-out');
                                var attendanceIn = $(this).data('attendance-in');
                                var attendanceOut = $(this).data('attendance-out');
                                var apprin = $(this).data('apprin');
                                var approut = $(this).data('approut');
                                console.log(photoIn, photoOut, attendanceIn,
                                    attendanceOut, apprin, approut);
                                // Set modal fields
                                $('#photoIn').attr('src', photoIn);
                                $('#photoOut').attr('src', photoOut);
                                $('#attendanceIn').val(attendanceIn);
                                $('#attendanceOut').val(attendanceOut);
                                if (apprin === 2) {
                                    $('#status-in').text('Tervalidasi').addClass(
                                        'badge bg-label-success');
                                } else {
                                    $('#status-in').text('Belum divalidasi').addClass(
                                        'badge bg-label-danger');
                                }
                                if (approut === 2) {
                                    $('#status-out').text('Tervalidasi').addClass(
                                        'badge bg-label-success');
                                } else {
                                    $('#status-out').text('Belum divalidasi').addClass(
                                        'badge bg-label-danger');
                                }
                            });
                            $('#dataTable').DataTable();

                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                } else {
                    alert('Silakan Pilih Bulan dan Tahun!');
                }
            });
            $('#btn-export').click(function() {
                var year = $('#year').val();
                var month = $('#month').val();
                var staff = $('#staff').val();
                if (year !== null && month !== null) {
                    var data = {
                        year: year,
                        month: month,
                        staff: staff,
                        method: 'EXCEL'
                    };
                    // console.log(data);
                    $.ajax({
                        url: "{{ route('report.attendance.bystaff.get') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(data, status, xhr) {
                            var filename = xhr.getResponseHeader('Content-Disposition').split(
                                'filename=')[1].replace(/['"]/g, '');
                            var a = document.createElement('a');
                            var url = window.URL.createObjectURL(data);
                            a.href = url;
                            a.download = filename;
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                } else {
                    alert('Silakan Pilih Bulan dan Tahun!');
                }
            });
        });
    </script>
@endpush
