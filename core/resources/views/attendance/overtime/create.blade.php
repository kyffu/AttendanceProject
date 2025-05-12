@extends('layouts.app')
@section('title', 'Pengajuan Jam Lembur')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Pengajuan Jam Lembur</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('attendance.overtime.store') }}">
                            @method('POST')
                            @csrf
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Tanggal Lembur</label>
                                    <input type="text" name="date" id="date" class="form-control"
                                        placeholder="Pilih tanggal lembur" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Durasi Lembur</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="duration"
                                            placeholder="Berapa jam Anda lembur?" onkeypress="return mustNumber(event)"
                                            autocomplete="off" required>
                                        <span class="input-group-text" id="basic-addon13">Jam</span>
                                    </div>
                                    <div class="form-text">Gunakan titik untuk memisahkan desimal (Misal : 5.5) !</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                <a href="{{ route('attendance.overtime.index') }}"
                                    class="btn btn-danger me-1 mb-1">Cancel</a>
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

            // Allow numbers (48-57) and period (46)
            if (char > 31 && (char < 48 || char > 57) && char != 46) {
                return false;
            }

            return true;
        }

        $(document).ready(function() {

            $('#date').flatpickr({
                dateFormat: "d-m-Y",
            });
        });
    </script>
@endpush
