@extends('layouts.app')
@section('title', 'Tambah Proyek')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Tambah Proyek</h5>
                    <a href="{{ route('project.index') }}" class="btn btn-danger me-1 mb-1">Kembali</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('project.store') }}">
                        @csrf
                        <div class="mb-3">
                            <p>Manager Proyek : <strong>{{ auth()->user()->name }}</strong></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Proyek</label>
                            <input type="text" class="form-control" name="project_name" placeholder="Isikan Nama Proyek" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan Proyek</label>
                            <textarea class="form-control" name="project_desc" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="text" class="form-control" name="start_date" id="start-date" placeholder="Pilih Tanggal Mulai Proyek" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="text" class="form-control" name="end_date" id="end-date" placeholder="Pilih Tanggal Selesai Proyek" required>
                        </div>

                        <hr>
                        <h5 class="mb-0 p-0"><strong>Data Mandor</strong></h5>
                        <div id="mandors-container" class="row">
                            <div class="mandor-item row g-3 mb-2 mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Mandor</label>
                                        <select name="mandor_name[]" class="form-select mandor-select" required>
                                            <option value="" disabled selected>Pilih Mandor</option>
                                            @foreach($mandors as $mandor)
                                                <option value="{{ $mandor->name }}">{{ $mandor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-group w-100">
                                        <label>Upah per Hari</label>
                                        <input type="text" name="mandor_salary_day[]" class="form-control mandor-salary-input" required>
                                    </div>
                                    <button type="button" class="btn btn-secondary add-mandor ms-2">+</button>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-0 p-0"><strong>Data Tukang</strong></h5>
                        <div id="craftsmen-container" class="row">
                            <div class="craftsman-item row g-3 mb-2 mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Tukang</label>
                                        <select name="worker_name[]" class="form-select worker-select" required>
                                            <option value="" disabled selected>Pilih Tukang</option>
                                            @foreach($tukangs as $tukang)
                                                <option value="{{ $tukang->name }}">{{ $tukang->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-group w-100">
                                        <label>Upah per Hari</label>
                                        <input type="text" name="salary_day[]" class="form-control tukang-salary-input" required>
                                    </div>
                                    <button type="button" class="btn btn-secondary add-worker ms-2">+</button>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
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
document.addEventListener('DOMContentLoaded', function () {
    const autoNumericOptions = {
        allowDecimalPadding: false,
        currencySymbol: "Rp",
        decimalCharacter: ",",
        digitGroupSeparator: ".",
        emptyInputBehavior: "zero",
        unformatOnSubmit: true
    };

    new AutoNumeric.multiple('.mandor-salary-input', autoNumericOptions);
    new AutoNumeric.multiple('.tukang-salary-input', autoNumericOptions);

    function refreshDropdownDisabling(selector) {
        const selectedValues = Array.from(document.querySelectorAll(selector))
            .map(select => select.value)
            .filter(val => val !== "");

        document.querySelectorAll(selector).forEach(select => {
            Array.from(select.options).forEach(option => {
                if (option.value === "") return;
                option.disabled = selectedValues.includes(option.value) && option.value !== select.value;
            });
        });
    }

    // ========== Tukang ==========
    const craftsmanContainer = document.getElementById('craftsmen-container');
    document.querySelector('.add-worker').addEventListener('click', addCraftsmanRow);

    function addCraftsmanRow() {
        let template = document.querySelector('.craftsman-item');
        let newRow = template.cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => input.value = '');
        let select = newRow.querySelector('select');
        select.value = '';
        select.addEventListener('change', () => refreshDropdownDisabling('.worker-select'));

        let button = newRow.querySelector('.add-worker');
        button.textContent = 'X';
        button.classList.remove('btn-secondary', 'add-worker');
        button.classList.add('btn-danger', 'remove-worker');
        button.addEventListener('click', () => {
            newRow.remove();
            refreshDropdownDisabling('.worker-select');
        });

        craftsmanContainer.appendChild(newRow);
        new AutoNumeric(newRow.querySelector('.tukang-salary-input'), autoNumericOptions);
        refreshDropdownDisabling('.worker-select');
    }

    craftsmanContainer.addEventListener('change', function (e) {
        if (e.target.classList.contains('worker-select')) {
            refreshDropdownDisabling('.worker-select');
        }
    });

    // ========== Mandor ==========
    const mandorContainer = document.getElementById('mandors-container');
    document.querySelector('.add-mandor').addEventListener('click', addMandorRow);

    function addMandorRow() {
        let template = document.querySelector('.mandor-item');
        let newRow = template.cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => input.value = '');
        let select = newRow.querySelector('select');
        select.value = '';
        select.addEventListener('change', () => refreshDropdownDisabling('.mandor-select'));

        let button = newRow.querySelector('.add-mandor');
        button.textContent = 'X';
        button.classList.remove('btn-secondary', 'add-mandor');
        button.classList.add('btn-danger', 'remove-mandor');
        button.addEventListener('click', () => {
            newRow.remove();
            refreshDropdownDisabling('.mandor-select');
        });

        mandorContainer.appendChild(newRow);
        new AutoNumeric(newRow.querySelector('.mandor-salary-input'), autoNumericOptions);
        refreshDropdownDisabling('.mandor-select');
    }

    mandorContainer.addEventListener('change', function (e) {
        if (e.target.classList.contains('mandor-select')) {
            refreshDropdownDisabling('.mandor-select');
        }
    });

    // ========== Date Picker ==========
    $(document).ready(function () {
        $('#start-date, #end-date').flatpickr({
            dateFormat: "d-m-Y"
        });
    });
});
</script>
@endpush
