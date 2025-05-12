@extends('layouts.app')
@section('title', 'Detail Proyek')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Detail Proyek</h5>
                        <a href="{{ route('project.index') }}" class="btn btn-danger me-1 mb-1">Kembali</a>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="lockedForm" action="{{ route('project.finish') }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="prid" id="prid" value="{{ Crypt::encryptString($project->id) }}">

                            <div class="mb-3">
                                <p>Manager Proyek : <strong>{{ $project->foreman->name }}</strong></p>
                            </div>

                            <div class="table-responsive text-nowrap">
                                <table class="table" id="dataTable">
                                    <tr><td>Nama Proyek</td><td>:</td><td>{{ $project->name }}</td></tr>
                                    <tr><td>Keterangan Proyek</td><td>:</td><td>{{ $project->desc }}</td></tr>
                                    <tr><td>Tanggal Mulai</td><td>:</td><td>{{ $project->start_date }}</td></tr>
                                    <tr><td>Tanggal Selesai</td><td>:</td><td>{{ $project->end_date }}</td></tr>
                                </table>
                            </div>

                            <hr>
                            <h5 class="mb-0 p-0"><strong>Data Mandor</strong></h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table" id="mandorSalaryTable">
                                    <thead>
                                        <tr>
                                            <th>Nama Mandor</th>
                                            <th>Jumlah Hari Kerja</th>
                                            <th>Upah per Hari</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_mandors as $data_mandor)
                                            <tr>
                                                <td>{{ $data_mandor->worker_name }}
                                                    <input type="hidden" name="mandor_wrid[]" value="{{ Crypt::encryptString($data_mandor->id) }}">
                                                </td>
                                                <td><input type="text" name="mandor_working_days[]" class="form-control" value="{{ $data_mandor->working_days }}" onkeypress="return mustNumber(event)" required></td>
                                                <td><input type="text" name="mandor_salary_day[]" class="form-control" value="{{ $data_mandor->salary_day }}" required></td>
                                                <td><input type="text" name="mandor_total[]" class="form-control bg-label-dark" readonly></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-center"><strong>TOTAL UPAH MANDOR</strong></td>
                                            <td id="mandor_totals"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

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
                                    <tbody>
                                        @foreach ($data_tukangs as $data_tukang)
                                            <tr>
                                                <td>{{ $data_tukang->worker_name }}
                                                    <input type="hidden" name="wrid[]" value="{{ Crypt::encryptString($data_tukang->id) }}">
                                                </td>
                                                <td><input type="text" name="working_days[]" class="form-control" value="{{ $data_tukang->working_days }}" onkeypress="return mustNumber(event)" required></td>
                                                <td><input type="text" name="salary_day[]" class="form-control" value="{{ $data_tukang->salary_day }}" required></td>
                                                <td><input type="text" name="total[]" class="form-control bg-label-dark" readonly></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-center"><strong>TOTAL UPAH TUKANG</strong></td>
                                            <td id="totals"></td>
                                        </tr>
                                        <tr style="border-top: 3px solid #dee2e6;">
                                            <td colspan="3" class="text-center pt-3"><strong>TOTAL UPAH KESELURUHAN</strong></td>
                                            <td id="totalsAll"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <br>
                            <h5 class="mb-0 p-0"><strong>Bukti Kehadiran</strong></h5>
                            <div class="mb-3">
                                <label for="attendancePhotos" class="form-label">Unggah Bukti Kehadiran</label>
                                <input type="file" id="attendancePhotos" name="attendance_photos[]" class="form-control" multiple accept="image/*">
                                <div class="form-text">
                                    Anda bisa mengunggah lebih dari satu bukti kehadiran. <strong>Hanya file JPEG & PNG maksimal 2MB</strong>
                                </div>
                            </div>

                            <div id="photosContainer"></div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Selesaikan Proyek</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script>
    function mustNumber(evt) {
        let charCode = (evt.which) ? evt.which : evt.keyCode;
        return !(charCode > 31 && (charCode < 48 || charCode > 57));
    }

    document.addEventListener('DOMContentLoaded', function () {
        const autoNumericOptions = {
            allowDecimalPadding: false,
            currencySymbol: "Rp",
            decimalCharacter: ",",
            digitGroupSeparator: ".",
            emptyInputBehavior: "zero",
            unformatOnSubmit: true
        };

        // Tukang
        const tukangTable = document.getElementById('salaryTable');
        const tukangSalaryFields = AutoNumeric.multiple('input[name="salary_day[]"]', autoNumericOptions);
        const tukangTotalFields = AutoNumeric.multiple('input[name="total[]"]', autoNumericOptions);
        const tukangTotals = new AutoNumeric('#totals', autoNumericOptions);

        // Mandor
        const mandorTable = document.getElementById('mandorSalaryTable');
        const mandorSalaryFields = AutoNumeric.multiple('input[name="mandor_salary_day[]"]', autoNumericOptions);
        const mandorTotalFields = AutoNumeric.multiple('input[name="mandor_total[]"]', autoNumericOptions);
        const mandorTotals = new AutoNumeric('#mandor_totals', autoNumericOptions);

        const totalAll = new AutoNumeric('#totalsAll', autoNumericOptions);

        function calculateTukang() {
            let total = 0;
            tukangTable.querySelectorAll('tbody tr').forEach((row, i) => {
                const days = parseInt(row.querySelector('input[name="working_days[]"]').value) || 0;
                const salary = parseFloat(tukangSalaryFields[i].getNumericString()) || 0;
                const rowTotal = days * salary;
                tukangTotalFields[i].set(rowTotal);
                total += rowTotal;
            });
            tukangTotals.set(total);
            calculateTotalAll();
        }

        function calculateMandor() {
            let total = 0;
            mandorTable.querySelectorAll('tbody tr').forEach((row, i) => {
                const days = parseInt(row.querySelector('input[name="mandor_working_days[]"]').value) || 0;
                const salary = parseFloat(mandorSalaryFields[i].getNumericString()) || 0;
                const rowTotal = days * salary;
                mandorTotalFields[i].set(rowTotal);
                total += rowTotal;
            });
            mandorTotals.set(total);
            calculateTotalAll();
        }

        function calculateTotalAll() {
            const totalTukang = parseFloat(tukangTotals.getNumericString()) || 0;
            const totalMandor = parseFloat(mandorTotals.getNumericString()) || 0;
            totalAll.set(totalTukang + totalMandor);
        }

        tukangTable.addEventListener('input', calculateTukang);
        mandorTable.addEventListener('input', calculateMandor);

        calculateTukang();
        calculateMandor();
    });

    // Preview Foto dan Tambahkan Deskripsi
    document.addEventListener('DOMContentLoaded', function () {
        const attendancePhotosInput = document.getElementById('attendancePhotos');
        const photosContainer = document.getElementById('photosContainer');

        attendancePhotosInput.addEventListener('change', function (event) {
            const files = event.target.files;
            photosContainer.innerHTML = '';

            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.classList.add('photo-preview', 'mb-3', 'border', 'p-2');

                    previewDiv.innerHTML = `
                        <div class="mb-2">
                            <img src="${e.target.result}" alt="Attendance Photo" class="img-thumbnail" width="150">
                        </div>
                        <div class="mb-3">
                            <label for="description_${index}" class="form-label">Deskripsi</label>
                            <input type="text" name="description[${index}]" id="description_${index}" class="form-control" placeholder="Isikan Deskripsi Foto" required>
                        </div>
                    `;
                    photosContainer.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            });
        });
    });
</script>
@endpush
