@extends('layouts.app')
@section('title', 'Tambah Gaji')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Tambah Gaji</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('settings.salary.store') }}">
                            @method('POST')
                            @csrf
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Deskripsi Gaji</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="description"
                                        placeholder="Mohon masukkan deskripsi gaji" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Jumlah Gaji</label>
                                <div class="col-sm-10">
                                    <input type="text" name="amount" class="form-control amount" id="amount">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                <button type="reset" class="btn btn-secondary me-1 mb-1">Reset</button>
                                <a href="{{ route('settings.salary.index') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
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
@endpush
