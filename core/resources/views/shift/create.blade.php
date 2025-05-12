@extends('layouts.app')
@section('title', 'Tambah Shift')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Tambah Shift</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('shift.store') }}">
                            @method('POST')
                            @csrf
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Nama Shift</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Silakan Masukkan Nama Shift" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Waktu Masuk</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="start_time"
                                        placeholder="Silakan Masukkan Waktu Masuk" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Waktu Keluar</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="end_time"
                                        placeholder="Silakan Masukkan Waktu Keluar" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Toleransi Keterlambatan</label>
                                <div class="input-group col-sm-10">
                                    <input type="text" name="late_tolerance"
                                        placeholder="Masukkan Toleransi Keterlambatan dalam menit" class="form-control"
                                        onkeypress="return mustNumber(event)" autocomplete="off" required>
                                    <span class="input-group-text">menit</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Send</button>
                                <button type="reset" class="btn btn-secondary me-1 mb-1">Reset</button>
                                <a href="{{ route('user.index') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
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
    </script>
@endpush
