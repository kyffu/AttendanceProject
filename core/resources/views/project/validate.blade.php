@extends('layouts.app')
@section('title', 'Validasi Proyek')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Validasi Proyek</h5>
                        <a href="{{ route('project.index') }}" class="btn btn-danger me-1 mb-1">Kembali</a>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="lockedForm" action="{{ route('project.accept') }}">
                            @method('POST')
                            @csrf
                            <input type="hidden" name="prid" id="prid"
                                value="{{ Crypt::encryptString($project->id) }}">
                            <div class="mb-3">
                                <p>Manager Proyek : <strong>{{ $project->foreman->name }}</strong></p>
                            </div>
                            <div class="table-responsive text-nowrap">
                                <table class="table" id="dataTable">
                                    <tr>
                                        <td>Nama Proyek</td>
                                        <td>:</td>
                                        <td>{{ $project->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan Proyek</td>
                                        <td>:</td>
                                        <td>{{ $project->desc }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Mulai</td>
                                        <td>:</td>
                                        <td>{{ $project->start_date }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Selesai</td>
                                        <td>:</td>
                                        <td>{{ $project->end_date }}</td>
                                    </tr>
                                </table>
                            </div>
                            <hr>
                            <h5 class="mb-0 p-0"><strong>Data Mandor</strong></h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table" id="salaryTable">
                                    <thead>
                                        <tr>
                                            <th>Nama Mandor</th>
                                            <th>Jumlah Hari Kerja</th>
                                            <th>Upah per Hari</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($data_mandors as $data_mandor)
                                        <tr>
                                            <td>{{ $data_mandor->worker_name }}</td>
                                            <td>{{ $data_mandor->working_days }}</td>
                                            <td class="amount">{{ $data_mandor->salary_day }}</td>
                                            <td class="amount">{{ $data_mandor->total_salary }}</td>
                                        </tr>
                                        @php
                                            $total += $data_mandor->total_salary;
                                        @endphp
                                    @endforeach
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-center"><strong>TOTAL UPAH MANDOR</strong></td>
                                            <td class="total">{{ $total }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <br>
                            <hr>
                            <h5 class="mb-0 p-0"><strong>Data Tukang</strong></h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table" id="salaryTable">
                                    <thead>
                                        <tr>
                                            <th>Nama Tukang</th>
                                            <th>Jumlah Hari Kerja</th>
                                            <th>Upah per Hari</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($data_tukangs as $worker)
                                        <tr>
                                            <td>{{ $worker->worker_name }}</td>
                                            <td>{{ $worker->working_days }}</td>
                                            <td class="amount">{{ $worker->salary_day }}</td>
                                            <td class="amount">{{ $worker->total_salary }}</td>
                                        </tr>
                                        @php
                                            $total += $worker->total_salary;
                                        @endphp
                                    @endforeach
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-center"><strong>TOTAL UPAH TUKANG</strong></td>
                                            <td class="total">{{ $total }}</td>
                                        </tr>
                                        <tr style="border-top: 3px solid #dee2e6;">
                                            <td colspan="3" class="text-center pt-3"><strong>TOTAL UPAH KESELURUHAN</strong></td>
                                            <td id="totalsAll"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <br>
                            <h5 class=""><strong>Bukti Kehadiran</strong></h5>
                            @foreach ($attendances as $attendance)
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ asset($attendance->photo_path) }}" alt="Attendance Photo" class="img-thumbnail"
                                        width="150">
                                </div>
                                <div class="mb-3">
                                    <p>Deskripsi : {{$attendance->description}}</p>
                                </div>
                            @endforeach
                            <div class="d-flex justify-content-end">
                                @if($project->status !== 4)
                                <button type="submit" id="btn-update" class="btn btn-primary me-1 mb-1">Validasi
                                    Proyek</button>
                                    @else
                                    <p>Telah divalidasi oleh : <strong>{{$project->validator->name}}</strong> pada {{$project->validated_at}}</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Basic with Icons -->
        </div>
    </div>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            const numericOptions = {
                allowDecimalPadding: false,
                currencySymbol: "Rp",
                decimalCharacter: ",",
                digitGroupSeparator: ".",
                emptyInputBehavior: "zero",
                unformatOnSubmit: true
            };
            AutoNumeric.multiple(".amount", null, numericOptions);
        });
    </script>

    <script type="text/javascript">
    $(document).ready(function () {
        const numericOptions = {
            allowDecimalPadding: false,
            currencySymbol: "Rp",
            decimalCharacter: ",",
            digitGroupSeparator: ".",
            emptyInputBehavior: "zero",
            unformatOnSubmit: true
        };

        const anElements = AutoNumeric.multiple(".total", null, numericOptions);

        // Tunggu hingga AutoNumeric selesai menginisialisasi
        setTimeout(() => {
            let totalMandor = 0;
            let totalTukang = 0;
            const mandorCount = @json(count($data_mandors));
            const tukangCount = @json(count($data_tukangs));

            console.log(mandorCount)

            anElements.forEach((an, index) => {

                const value = an.getNumber();
                if (index < mandorCount) {
                    totalMandor += value;
                } else {
                    totalTukang += value;
                }
            });

            const totalKeseluruhan = totalMandor + totalTukang;

            // Format total keseluruhan ke Rupiah
            const formattedTotal = new AutoNumeric('#totalsAll', null, numericOptions);
            formattedTotal.set(totalKeseluruhan);
        }, 300); // Delay kecil agar AutoNumeric siap
    });
</script>

@endpush
