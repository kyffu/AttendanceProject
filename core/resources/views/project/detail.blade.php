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
                        <form method="POST" id="projectForm" action="{{ route('project.update') }}">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="prid" id="prid" value="{{ Crypt::encryptString($project->id) }}">

                            <div class="mb-3">
                                <p>Manager Proyek : <strong>{{ $project->foreman->name }}</strong></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Proyek</label>
                                <input type="text" class="form-control" name="project_name" value="{{ $project->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan Proyek</label>
                                <textarea class="form-control" name="project_desc" rows="2" required>{{ $project->desc }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="text" class="form-control" name="start_date" id="start-date"
                                    value="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $project->start_date)->format('d-m-Y') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="text" class="form-control" name="end_date" id="end-date"
                                    value="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $project->end_date)->format('d-m-Y') }}" required>
                            </div>

                            <hr>
                            <h5><strong>Data Mandor</strong></h5>
                            <div id="mandors-container">
                                @foreach ($data_mandors as $data_mandor)
                                    <div class="mandor-item row g-3 mb-2">
                                        <div class="col-md-4">
                                            <label>Nama Mandor</label>
                                            <select name="mandor_name[]" class="form-select mandor-select" required>
                                                <option value="" disabled>Pilih Mandor</option>
                                                @foreach ($mandors as $mandor)
                                                    <option value="{{ $mandor->name }}" {{ $mandor->name == $data_mandor->worker_name ? 'selected' : '' }}>
                                                        {{ $mandor->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Upah per Hari</label>
                                            <input type="text" name="mandor_salary_day[]" class="form-control mandor-salary-input" value="{{ $data_mandor->salary_day }}" required>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-mandor">X</button>
                                        </div>
                                    </div>
                                @endforeach
                                <button type="button" class="btn btn-secondary add-mandor">Tambah Mandor</button>
                            </div>

                            <hr>
                            <h5><strong>Data Tukang</strong></h5>
                            <div id="workers-container">
                                @foreach ($data_tukangs as $data_tukang)
                                    <div class="worker-item row g-3 mb-2">
                                        <div class="col-md-4">
                                            <label>Nama Tukang</label>
                                            <select name="worker_name[]" class="form-select worker-select" required>
                                                <option value="" disabled>Pilih Tukang</option>
                                                @foreach ($tukangs as $tukang)
                                                    <option value="{{ $tukang->name }}" {{ $tukang->name == $data_tukang->worker_name ? 'selected' : '' }}>
                                                        {{ $tukang->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Upah per Hari</label>
                                            <input type="text" name="salary_day[]" class="form-control tukang-salary-input" value="{{ $data_tukang->salary_day }}" required>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-worker">X</button>
                                        </div>
                                    </div>
                                @endforeach
                                <button type="button" class="btn btn-secondary add-worker">Tambah Tukang</button>
                            </div>

                            <br>
                            <div class="alert alert-primary">
                                <ul class="m-0">
                                    <li><strong>Simpan Sementara:</strong> Masih bisa mengubah semua data proyek dan tukang</li>
                                    <li><strong>Simpan Permanen:</strong> Hanya bisa ubah upah dan isi hari kerja</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary me-2">Simpan Sementara</button>
                                <button type="button" id="btn-lock" class="btn btn-outline-success">Simpan Permanen</button>
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

function unformatAutoNumericFields() {
    document.querySelectorAll('.mandor-salary-input, .tukang-salary-input').forEach(input => {
        const anInstance = AutoNumeric.getAutoNumericElement(input);
        if (anInstance) {
            input.value = anInstance.getNumber();
        }
    });
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

    let arrMandor = []
    const tukangs = @json($tukangs);

    new AutoNumeric.multiple('.mandor-salary-input', autoNumericOptions);
    new AutoNumeric.multiple('.tukang-salary-input', autoNumericOptions);

    function refreshSelectDisabling(selector) {
        const selected = [...document.querySelectorAll(selector)]
            .map(sel => sel.value)
            .filter(v => v !== "");

        document.querySelectorAll(selector).forEach(select => {
            [...select.options].forEach(opt => {
                if (opt.value === "") return;
                opt.disabled = selected.includes(opt.value) && opt.value !== select.value;
            });
        });
    }

    // Mandor
    const mandorContainer = document.getElementById('mandors-container');
    mandorContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-mandor')) {
            const item = mandorContainer.querySelector('.mandor-item');
            const clone = item.cloneNode(true);
            clone.querySelector('input').value = '';
            clone.querySelector('select').value = '';
            clone.querySelector('.remove-mandor')?.remove();
            const rmBtn = document.createElement('button');
            rmBtn.type = 'button';
            rmBtn.className = 'btn btn-danger remove-mandor';
            rmBtn.textContent = 'X';
            clone.querySelector('.col-md-2')?.appendChild(rmBtn);
            mandorContainer.insertBefore(clone, e.target);
            new AutoNumeric(clone.querySelector('.mandor-salary-input'), autoNumericOptions);
            refreshSelectDisabling('.mandor-select');
        }

        if (e.target.classList.contains('remove-mandor')) {
            e.target.closest('.mandor-item').remove();
            refreshSelectDisabling('.mandor-select');
            updateRemoveButtonsState();

            arrMandor = Array.from(mandorContainer.querySelectorAll('.mandor-select')).map(select => select.value);
            refreshTukangOptions();
        }
    });

    // Fungsi untuk mengecek dan disable tombol remove jika tinggal 1
    function updateRemoveButtonsState() {
        const mandorItems = mandorContainer.querySelectorAll('.mandor-item');
        const removeButtons = mandorContainer.querySelectorAll('.remove-mandor');
        
        const workerItems = workerContainer.querySelectorAll('.worker-item');
        const removeButtonsTukang = workerContainer.querySelectorAll('.remove-worker');

        if (mandorItems.length <= 1) {
            removeButtons.forEach(btn => btn.disabled = true);
        } else {
            removeButtons.forEach(btn => btn.disabled = false);
        }

        if (workerItems.length <= 1) {
            removeButtonsTukang.forEach(btn => btn.disabled = true);
        } else {
            removeButtonsTukang.forEach(btn => btn.disabled = false);
        }
    }

   

    mandorContainer.addEventListener('change', function (e) {
        if (e.target.classList.contains('mandor-select')) {
            refreshSelectDisabling('.mandor-select');

            arrMandor = Array.from(mandorContainer.querySelectorAll('.mandor-select')).map(select => select.value);
            refreshTukangOptions();
        }
    });

    function refreshTukangOptions() {
        console.log('cek worker-select ada berapa:', $('.worker-select').length);
        $('.worker-select').each(function () {
            const $select = $(this);
            $select.empty(); // clear semua option dulu

            let filteredTukang = tukangs;

            if (arrMandor.length > 0) {
                filteredTukang = tukangs.filter(t => arrMandor.includes(t.mandor));
            }

            // Buat optionnya
            $select.append('<option value="">-- Pilih Tukang --</option>');

            filteredTukang.forEach(t => {
                $select.append(`<option value="${t.name}">${t.name}</option>`);
            });
        });
    }

    // Tukang
    const workerContainer = document.getElementById('workers-container');
    workerContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-worker')) {
            const item = workerContainer.querySelector('.worker-item');
            const clone = item.cloneNode(true);
            clone.querySelector('input').value = '';
            clone.querySelector('select').value = '';
            clone.querySelector('.remove-worker')?.remove();
            const rmBtn = document.createElement('button');
            rmBtn.type = 'button';
            rmBtn.className = 'btn btn-danger remove-worker';
            rmBtn.textContent = 'X';
            clone.querySelector('.col-md-2')?.appendChild(rmBtn);
            workerContainer.insertBefore(clone, e.target);
            new AutoNumeric(clone.querySelector('.tukang-salary-input'), autoNumericOptions);
            refreshSelectDisabling('.worker-select');
        }

        if (e.target.classList.contains('remove-worker')) {
            e.target.closest('.worker-item').remove();
            refreshSelectDisabling('.worker-select');
            updateRemoveButtonsState();
        }
    });

    workerContainer.addEventListener('change', function (e) {
        if (e.target.classList.contains('worker-select')) {
            refreshSelectDisabling('.worker-select');
        }
    });

    // Date Picker
    flatpickr("#start-date", { dateFormat: "d-m-Y" });
    flatpickr("#end-date", { dateFormat: "d-m-Y" });


    document.getElementById('btn-lock').addEventListener('click', function () {
        const konfirmasi = confirm('Apakah kamu yakin ingin menyimpan secara permanen? Data tidak bisa diubah kecuali upah dan hari kerja.');
        if (konfirmasi) {
            unformatAutoNumericFields();
            document.getElementById('projectForm').setAttribute('action', "{{ route('project.lock') }}");
            document.getElementById('projectForm').submit();
        }
    });

    // Handle the 'Simpan Sementara' button click
    document.getElementById('btn-update').addEventListener('click', function() {
        unformatAutoNumericFields();
        document.getElementById('projectForm').setAttribute('action',
            "{{ route('project.update') }}");
    });

     updateRemoveButtonsState();
});
</script>
@endpush
