@extends('layouts.app')
@section('title', 'Laporan Gaji')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card  mb-2">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header mb-0">
                <h5>Laporan Gaji</h5>
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
                    <input type="hidden" name="staff" id="staff" value="{{ Crypt::encryptString($user->id) }}">
                     
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
            </div>
        </div>
        <div id="report">
            
        </div>
    </div>
@stop
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#year').select2({
                theme: 'bootstrap-5'
            });
            $('#btn-show').click(function() {
                var year = $('#year').val();
                var staff = $('#staff').val();
                if (year !== null) {
                    $('#report').empty();
                    var data = {
                        year: year,
                        staff: staff,
                        method: 'GET'
                    };

                    $.ajax({
                        url: "{{ route('report.payroll.get') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,
                        success: function(response) {
                            $('#report').html(response.html);
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                } else {
                    alert('Silakan Pilih Bulan dan Tahun!');
                }
            });
            $(document).on('click', '.btn-payroll', function() {
                $('#spinner-proll').show();
                $('#preport').empty();
                var payroll = $(this).data('payroll');
                $('#modalShow').modal('show');
                $.ajax({
                    url: "{{ route('report.payroll.show') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        payroll: payroll,
                        method: 'GET'
                    },
                    success: function(response) {
                        $('#preport').html(response.html);
                        $('#spinner-proll').hide();
                        $('#btn-print').data('payroll', payroll);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
            $(document).on('click', '.btn-allow', function() {
                var payroll = $(this).data('payroll');
                $('#modalAllowance select[name="allow"]').val('');
                $('#modalAllowance input[name="alid"]').remove();
                $('#modalAllowance').modal('show');
                $('#allow').val(payroll);
                $.ajax({
                    url: "{{ route('report.payroll.allow.get') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        payroll: payroll
                    },
                    success: function(response) {
                        if (response.alwid) {
                            $('#modalAllowance select[name="allow"] option').each(function() {
                                if ($(this).data('wolla') == response.alwid) {
                                    $(this).prop('selected', true);
                                }
                            });
                            $('#modalAllowance form').append(
                                '<input type="hidden" name="alid" value="' + response.alid +
                                '">');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
    <script>
        function printDivToPDF() {
            var {
                jsPDF
            } = window.jspdf;
            var printDiv = document.getElementById('preport');
            var fileName = document.getElementById('filename').value;
            var spinner = document.getElementById('spinner-print');

            spinner.style.display = 'block';
            html2canvas(printDiv, {
                scale: 3,
                useCORS: true
            }).then(function(canvas) {
                var imgData = canvas.toDataURL('image/png');
                var pdf = new jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: 'a4'
                });
                var pageWidth = pdf.internal.pageSize.getWidth();
                var pageHeight = pdf.internal.pageSize.getHeight();
                var imgWidth = canvas.width * 0.264583;
                var imgHeight = canvas.height * 0.264583;
                var ratio = Math.min(pageWidth / imgWidth, pageHeight / imgHeight);
                var newWidth = imgWidth * ratio;
                var newHeight = imgHeight * ratio;
                pdf.addImage(imgData, 'PNG', 0, 0, newWidth, newHeight);
                pdf.save(fileName + '.pdf');
                spinner.style.display = 'none';
            });
        }
    </script>
@endpush
