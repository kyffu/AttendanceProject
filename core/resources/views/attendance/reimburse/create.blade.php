@extends('layouts.app')
@section('title', 'Pengajuan Reimburse')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Pengajuan Reimburse</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('attendance.reimburse.store') }}" enctype="multipart/form-data">
                            @method('POST')
                            @csrf
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Tanggal Reimburse</label>
                                    <input type="text" name="date" id="date" class="form-control"
                                        placeholder="Pilih tanggal reimburse" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Keterangan Reimburse</label>
                                    <input type="text" name="desc" id="desc" class="form-control"
                                        placeholder="Isikan Keterangan Reimburse" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Jumlah Reimburse</label>
                                    <input type="text" name="amount" class="form-control amount"
                                        placeholder="Isikan Jumlah Reimburse" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="mb-4">
                                    <label for="formFile" class="form-label">Bukti Reimburse</label>
                                    <input class="form-control" type="file" name="evidence" id="evidence" accept="image/jpeg" required>
                                </div>
                                <div class="form-text">Gambar harus bertipe Jpeg/Jpg dan maksimal berukuran sebesar 2MB!</div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                <a href="{{ route('attendance.reimburse.index') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
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
                       
            $('#date').flatpickr({
                dateFormat: "d-m-Y",
            });
        });
    </script>
@endpush
