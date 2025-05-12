<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PAYROLL | SATELYTE </title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datepicker/css/bootstrap-datepicker.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
    </style>
    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/autoNumeric.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>

<body onload="window.print();return false;">
    @php
        $month = \Carbon\Carbon::createFromFormat('Y-m', $title);
    @endphp
    <div class="card p-0 m-0">
        <div class="card-header">
            <img src="{{ url('assets/img/logo-slip.jpg') }}" alt="Logo" width="30%" height="auto">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h5 class="pt-3"><strong>DAFTAR ABSENSI KARYAWAN</strong></h5>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <table class="table-sm">
                    <tr>
                        <td>Bulan</td>
                        <td class="border">{{ $month->translatedFormat('F') }}</td>
                    </tr>
                    <tr>
                        <td>Tgl. Awal</td>
                        <td class="border">{{ $month->startOfMonth()->format('d/m/Y') }}</td>
                        <td colspan="3">&nbsp;</td>
                        <td>Tgl. Akhir</td>
                        <td class="border">{{ $month->endOfMonth()->format('d/m/Y') }}</td>
                    </tr>
                </table>
                <div class="d-flex justify-content-end">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table" id="dataTable">
                    <thead class="table-bordered">
                        <tr>
                            <th rowspan="3" class="text-center" style="vertical-align: middle;" width="15%">
                                Nama
                            </th>
                            <th colspan="{{ count($dates) }}" class="text-center border">Hari/Tanggal</th>
                        </tr>
                        <tr>
                            @foreach ($dates as $index => $date)
                                <th class="p-0 text-center border">
                                    {{ \Illuminate\Support\Str::substr(\Carbon\Carbon::parse($date)->translatedFormat('l'), 0, 3) }}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($dates as $index => $date)
                                <th class="p-0 text-center border">{{ \Carbon\Carbon::parse($date)->format('d') }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->name }}</td>
                            @php
                                $hadir = 0;
                                $is = 0;
                                $ph = 0;
                            @endphp
                            @foreach ($dates as $date)
                                @php
                                    $formattedDate = \Carbon\Carbon::parse($date)->format('Y-m-d');
                                    $attendanceDate = \Carbon\Carbon::parse($date);
                                    $isWeekend = $attendanceDate->isSaturday() || $attendanceDate->isSunday();
                                    $isHoliday = in_array($formattedDate, $holidays);

                                    if ($isHoliday) {
                                        echo '<td class="bg-warning text-white p-0 text-center border">PH</td>';
                                        $ph++;
                                        continue;
                                    }
                                    if ($isWeekend) {
                                        echo '<td class="bg-danger text-white p-0 text-center border">OFF</td>';
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
                                    <td class="p-0 text-center border">
                                        <span class="badge bg-label-dark me-1">{{ $absentTypeSymbol }}</span>
                                        @php
                                            $is++;
                                        @endphp
                                    </td>
                                @elseif ($attendance)
                                    @if ($attendance->approved == 2 && $attendance->approved_out == 2)
                                        <td class="text-success p-0 text-center border">H</td>
                                        @php
                                            $hadir++;
                                        @endphp
                                    @elseif($attendance->approved == 1 && $attendance->approved_out == 1)
                                        <td class="text-info p-0 text-center border">i</td>
                                    @elseif($attendance->time_out == null)
                                        <td class="text-warning p-0 text-center border">!</td>
                                    @else
                                        <td class="text-danger p-0 text-center border">X</td>
                                    @endif
                                @else
                                    <td class="text-danger p-0 text-center border">X</td>
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="row">
                <div class="col-3">
                    <table class="table table-bordered table-sm text-center">
                        <thead>
                            <tr class="table-success">
                                <th colspan="4">Rekapan</th>
                            </tr>
                            <tr class="table-label-success">
                                <th>H</th>
                                <th>I/S</th>
                                <th>PH</th>
                                <th>CK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ $hadir }}</td>
                                <td>{{ $is }}</td>
                                <td>{{ $ph }}</td>
                                <td>0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-3">
                    <p class="p-0 mb-0"><span>H</span>: Hadir</p>
                    <p class="p-0 mb-0"><span>!</span>: Belum Presensi Keluar</p>
                    <p class="p-0 mb-0"><span>X</span>: Tidak Hadir</p>
                    <p class="p-0 mb-0"><span>i</span>: Menunggu Validasi</p>
                    @foreach ($masters as $master)
                        <p class="p-0 mb-0">
                            <span>{{ strtoupper(substr($master->name, 0, 1)) }}</span>:{{ $master->name }}
                        </p>
                    @endforeach
                </div>
            </div>
            <hr>
            <h6><strong>RINCIAN PERHITUNGAN GAJI</strong></h6>
            <br>
            <div class="row">
                <div class="col-3">
                    <div class="row">
                        <p class="p-0"><strong>A. Gross Payment</strong></p>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr class="table-success">
                                    <th>No</th>
                                    <th>Hari Masuk</th>
                                    <th>Nominal</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td>1</td>
                                    <td>{{ $hadir }}</td>
                                    @php
                                        $salary = $user->position->salaries->amount;
                                    @endphp
                                    <td class="amount">{{ $salary }}</td>
                                    <td class="amount">{{ $totalGP = $salary * $hadir }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <p class="p-0"><strong>B. Reimburse (Konsumsi dan Lain Lain)</strong></p>
                        <table class="table table-bordered table-sm text-center">
                            <thead>
                                <tr class="table-success">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $r = 1;
                                    $tr = 0;
                                @endphp
                                @foreach ($reimburses as $item)
                                    <tr>
                                        <td>{{ $r++ }}</td>
                                        <td>{{ $item->reimbursement_date }}</td>
                                        <td class="amount">{{ $item->amount }}</td>
                                        <td class="amount">{{ $item->amount }}</td>
                                    </tr>
                                    @php
                                        $tr += $item->amount;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="3"><strong>TOTAL</strong></td>
                                    <td class="amount">{{ $tr }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-1"></div>
                <div class="col-4">
                    <div class="row">
                        <p class="p-0"><strong>C. Lembur ( Overtime )</strong></p>
                        <table class="table table-bordered table-sm text-center">
                            <thead>
                                <tr class="table-success">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Jam</th>
                                    <th>Jumlah Rupiah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $l = 1;
                                    $tlh = 0;
                                @endphp
                                @foreach ($overtimes as $ovt)
                                    <tr>
                                        <td>{{ $l++ }}</td>
                                        <td>{{ $ovt->date }}</td>
                                        <td>{{ $ovt->hours }} Jam</td>
                                        <td class="amount">{{ $ovt->hours * 16250 }}</td>
                                    </tr>
                                    @php
                                        $tlh += $ovt->hours;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="2"><strong>TOTAL</strong></td>
                                    <td>{{ $tlh }} Jam</td>
                                    <td class="amount">{{ $tlr = $tlh * 16250 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    @if ($allowance)
                        <div class="row">
                            <p class="p-0"><strong>D. Tunjangan</strong></p>
                            <table class="table table-bordered table-sm text-center">
                                <thead>
                                    <tr class="table-success">
                                        <th>No</th>
                                        <th>Jenis Tunjangan</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $allowance->allowance->name }}</td>
                                        <td class="amount">{{ $allows = $allowance->allowance->amount }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>TOTAL</strong></td>
                                        <td class="amount">{{ $allows }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="row">
                            <p class="p-0"><strong>D. INCOME NET ( A + B + C )</strong></p>
                            <table class="table table-bordered table-sm text-center">
                                <thead>
                                    <tr class="table-success">
                                        <th>Total Gross Payment</th>
                                        <th>Reimburse</th>
                                        <th>Lembur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="amount">{{ $totalGP }}</td>
                                        <td class="amount">{{ $tr }}</td>
                                        <td class="amount">{{ $tlr }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>TOTAL</strong></td>
                                        <td class="amount">{{ $totalGaji = $totalGP + $tr + $tlr }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <br>
                            <p class="p-0 mb-0"><strong>Terbilang:</strong></p>
                            <p class="p-0">
                                "{{ ucwords(Riskihajar\Terbilang\Facades\Terbilang::make($totalGaji)) }}"
                            </p>
                        </div>
                    @endif
                </div>
                <div class="col-1">
                </div>
                <div class="col-3">
                    @if ($allowance)
                        <div class="row">
                            <p class="p-0"><strong>E. INCOME NET ( A + B + C + D)</strong></p>
                            <table class="table table-bordered table-sm text-center">
                                <thead>
                                    <tr class="table-success">
                                        <th>Total Gross Payment</th>
                                        <th>Reimburse</th>
                                        <th>Lembur</th>
                                        <th>Tunjangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="amount">{{ $totalGP }}</td>
                                        <td class="amount">{{ $tr }}</td>
                                        <td class="amount">{{ $tlr }}</td>
                                        <td class="amount">{{ $allows }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><strong>TOTAL</strong></td>
                                        <td class="amount">{{ $totalGaji = $totalGP + $tr + $tlr + $allows }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="row">
                            <br>
                            <p class="p-0 mb-0"><strong>Terbilang:</strong></p>
                            <p class="p-0">
                                "{{ ucwords(Riskihajar\Terbilang\Facades\Terbilang::make($totalGaji)) }}"
                            </p>
                        </div>
                    @endif
                    <div class="row">
                        <table>
                            <tr>
                                <td>Dibuat</td>
                                <td>&emsp;&emsp;</td>
                                <td>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <br><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center">
                                    {{ \App\Models\Settings::getVal('nama_ttd_slip_gaji') ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <strong>{{ \App\Models\Settings::getVal('posisi_ttd_slip_gaji') ?? '' }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="p-0">
                                    <br><br><br><br>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
