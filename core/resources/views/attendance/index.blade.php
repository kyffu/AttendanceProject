@extends('layouts.app')
@section('title', 'Kehadiran')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @php
            $now = \Carbon\Carbon::now()->format('Y-m-d');
            $absence = auth()
                ->user()
                ->absents->first(function ($abs) use ($now) {
                    return \Carbon\Carbon::parse($abs->start_date)->format('Y-m-d') <= $now &&
                        \Carbon\Carbon::parse($abs->end_date)->format('Y-m-d') >= $now;
                });
            $isWeekend = \Carbon\Carbon::now()->isSaturday() || \Carbon\Carbon::now()->isSunday();
        @endphp
        <div class="card mb-2">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Informasi Kehadiran Harian</h5>
                @if ($isWeekend)
                @else
                    @if (!$absence)
                        @if (!$attend || !$attend->time_out)
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('attendance.record') }}" class="btn btn-primary ">Rekam Kehadiran</a>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
            <div class="card-body">
                <h5><strong>{{ \Carbon\Carbon::now()->locale('id_ID')->isoFormat('LLLL') }}</strong></h5>
                <div class="row mb-0">
                    <div class="col-4">
                        <p class="text-heading fw-medium">Nama</p>
                    </div>
                    <div class="col-4">
                        <p class="text-heading fw-medium">{{ auth()->user()->name }}</p>
                    </div>
                </div>
                @if ($isHoliday)
                <div class="alert alert-warning" role="alert">Tidak dapat presensi, Hari {{ $hTitle }}</div>
                @elseif($isWeekend)
                <div class="alert alert-danger" role="alert">Tidak dapat presensi, ini hari Libur, Selamat istirahat
                    :)</div>
                @else
                    @if ($absence)
                        <div class="alert alert-primary" role="alert">Tidak dapat presensi, karena dalam catatan sistem
                            Anda
                            sedang <strong>{{ $absence->master->name }}</strong>.</div>
                    @else
                        <div class="row mb-0">
                            <div class="col-4">
                                <p class="text-heading fw-medium">Batas Waktu Presensi Masuk</p>
                            </div>
                            <div class="col-4">
                                <p class="text-heading fw-medium">{{ $shift->shift->start_time }}</p>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-4">
                                <p class="text-heading fw-medium">Batas Waktu Presensi Keluar</p>
                            </div>
                            <div class="col-4">
                                <p class="text-heading fw-medium">{{ $shift->shift->end_time }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-body mb-1">
                            <div class="col-4">
                                <p class="text-heading fw-medium">Presensi Masuk</p>
                            </div>
                            <div class="col-4">
                                <p class="text-heading fw-medium">
                                    {{ $attend ? ($attend->time_in ? \Carbon\Carbon::parse($attend->time_in)->format('H:i:s') : 'Belum Presensi') : 'Belum ada presensi masuk' }}
                                </p>
                            </div>
                        </div>
                        <div class="row text-body mb-1">
                            <div class="col-4">
                                <p class="text-heading fw-medium">Presensi Keluar</p>
                            </div>
                            <div class="col-4">
                                <p class="text-heading fw-medium">
                                    {{ $attend ? ($attend->time_out ? \Carbon\Carbon::parse($attend->time_out)->format('H:i:s') : 'Belum Presensi') : 'Belum ada presensi masuk' }}
                                </p>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Rekap Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="row">
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
                </div>
                <br>
                <div id="report">

                </div>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->
    </div>
@stop
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#year').select2({
                theme: 'bootstrap-5'
            });
            $('#month').select2({
                theme: 'bootstrap-5'
            });
            $('#btn-show').click(function() {
                var year = $('#year').val();
                var month = $('#month').val();
                if (year !== null && month !== null) {
                    $('#col-spinner').show();
                    $('#report').empty();
                    var data = {
                        year: year,
                        month: month,
                        method: 'GET'
                    };

                    $.ajax({
                        url: "{{ route('attendance.report') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,
                        success: function(response) {
                            $('#col-spinner').hide();
                            $('#report').html(response);
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
            // $('#btn-print').click(function() {
            //     var year = $('#year').val();
            //     var month = $('#month').val();
            //     $('#download-modal').modal('show');
            //     $('#download-modal').modal({
            //         backdrop: 'static',
            //         keyboard: false
            //     });
            //     $('#download-modal').on('hide.bs.modal', function(e) {
            //         e.preventDefault();
            //     });
            //     var data = {
            //         year: year,
            //         month: month,
            //         method: 'PRINT'
            //     };
            //     $.ajax({
            //         url: "{{ route('attendance.report') }}",
            //         method: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: data,
            //         xhrFields: {
            //             responseType: 'blob'
            //         },
            //         success: function(response, status, xhr) {
            //             $('#download-modal').off('hide.bs.modal');
            //             $('#download-modal').modal('hide');
            //             var contentDisposition = xhr.getResponseHeader(
            //                 'Content-Disposition');
            //             if (contentDisposition) {
            //                 var filename = contentDisposition.split('filename=')[1].replace(
            //                     /"/g, '');
            //                 var blob = new Blob([response], {
            //                     type: 'application/pdf'
            //                 });
            //                 var link = document.createElement('a');
            //                 link.href = window.URL.createObjectURL(blob);
            //                 link.download = filename;
            //                 link.click();
            //             } else {
            //                 console.error(
            //                     'Content-Disposition header is not present in the response.'
            //                 );
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             if (xhr.status === 404) {
            //                 $('#download-modal').off('hide.bs.modal');
            //                 $('#download-modal').modal('hide');
            //                 $('#no-transaction').modal('show');
            //             }
            //         }
            //     });
            // });
            // $('#btn-excel').click(function() {
            //     var year = $('#year').val();
            //     var month = $('#month').val();
            //     var data = {
            //         year: year,
            //         month: month,
            //         method: 'EXCEL'
            //     };
            //     $.ajax({
            //         url: "{{ route('attendance.report') }}",
            //         method: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: data,
            //         xhrFields: {
            //             responseType: 'blob'
            //         },
            //         success: function(data, status, xhr) {
            //             var filename = xhr.getResponseHeader('Content-Disposition').split(
            //                 'filename=')[1].replace(/['"]/g, '');
            //             var a = document.createElement('a');
            //             var url = window.URL.createObjectURL(data);
            //             a.href = url;
            //             a.download = filename;
            //             document.body.append(a);
            //             a.click();
            //             a.remove();
            //             window.URL.revokeObjectURL(url);
            //         },
            //         error: function(xhr, status, error) {
            //             if (xhr.status === 404) {
            //                 $('#download-modal').off('hide.bs.modal');
            //                 $('#download-modal').modal('hide');
            //                 $('#no-transaction').modal('show');
            //             }
            //         }
            //     });
            // });
        });
    </script>
@endpush
