@extends('layouts.app')
@section('title', 'Jenis Ketidakhadiran')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Tambah Jenis Ketidakhadiran</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('settings.absent.store') }}">
                            @method('POST')
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-name">Nama Jenis Ketidakhadiran</label>
                                <input type="text" class="form-control" name="name"
                                    placeholder="Contoh: Cuti Melahirkan" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-name">Kuota per bulan</label>
                                <input type="text" class="form-control" name="quota"
                                    placeholder="... kali"
                                    onkeypress="return mustNumber(event)" autocomplete="off" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-email">Membutuhkan Bukti?</label>
                                <div class="input-group input-group-merge">
                                    <div class="form-check">
                                        <input class="form-check-input" name="evc" type="checkbox" value="1"
                                            id="defaultCheck3">
                                        <label class="form-check-label" for="defaultCheck3"> Butuh</label>
                                    </div>
                                </div>
                                <div class="form-text">Bukti bisa berupa foto surat keterangan dokter atau surat lain yang
                                    mendukung</div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                <button type="reset" class="btn btn-secondary me-1 mb-1">Reset</button>
                                <a href="{{ route('settings.absent.index') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
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
        function mustNumber(key) {
            key = (key) ? key : window.event;
            var char = (key.which) ? key.which : key.keyCode;
            if (char > 31 && (char < 48 || char > 57)) {
                return false;
            }
            return true;
        }
        $(document).ready(function() {

        });
    </script>
@endpush
