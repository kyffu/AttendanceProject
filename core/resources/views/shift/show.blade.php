@extends('layouts.app')
@section('title', 'Detail Shift')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Shift</h5>
                    <div class="card-body">
                        <form action="{{ route('shift.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="svty" value="{{ Crypt::encryptString($shift->id) }}" />
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Nama Shift</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Silakan Masukkan Nama Shift" value="{{ $shift->name }}" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Waktu Masuk</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="start_time" id="start"
                                        placeholder="Silakan Masukkan Waktu Masuk" required
                                        value="{{ $shift->start_time }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Waktu Keluar</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="end_time" id="end"
                                        placeholder="Silakan Masukkan Waktu Keluar" required
                                        value="{{ $shift->end_time }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Toleransi Keterlambatan</label>
                                <div class="input-group col-sm-10">
                                    <input type="text" name="late_tolerance" id="late"
                                        placeholder="Masukkan Toleransi Keterlambatan dalam menit" class="form-control"
                                        value="{{ $shift->late_tolerance }}" onkeypress="return mustNumber(event)"
                                        autocomplete="off" required>
                                    <span class="input-group-text">menit</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="button" id="btn-edit" class="btn btn-primary me-2">Ubah</button>
                                <button type="submit" id="btn-submit" style="display: none;"
                                    class="btn btn-success me-2">Simpan Perubahan</button>
                                <button type="button" id="btn-cancel" style="display: none;"
                                    class="btn btn-outline-danger me-2">Batal</button>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary"
                                    id="btn-back">Kembali</a>
                            </div>
                        </form>
                    </div>
                    <!-- /Account -->
                </div>
                <div class="card">
                    <h5 class="card-header">Hapus data</h5>
                    <div class="card-body">
                        <div class="mb-3 col-12 mb-0">
                            <div class="alert alert-danger">
                                <h6 class="alert-heading mb-1">Apakah Anda yakin akan menghapus <strong>data ini</strong>
                                    ?</h6>
                                <p class="mb-0">Setelah Anda menghapus <strong>data ini</strong> data tidak dapat
                                    dipulihkan kembali, Mohon hati-hati!
                                </p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('shift.destroy') }}">
                            <input type="hidden" name="ytvs" value="{{ Crypt::encryptString($shift->id) }}">
                            @csrf
                            @method('DELETE')
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="confirmation"
                                    @if (Session::has('autofocus')) autofocus="on" @endif />
                                <label class="form-check-label" for="accountActivation"
                                    @if (Session::has('autofocus')) style="color:red;" @endif>Saya mengkonfirmasi
                                    penghapusan data</label>
                            </div>
                            <button type="submit" class="btn btn-danger deactivate-account">Hapus Data</button>
                        </form>
                    </div>
                </div>
            </div>
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
            $("#name").prop('disabled', true);
            $("#start").prop('disabled', true);
            $("#end").prop('disabled', true);
            $("#late").prop('disabled', true);

            $("#btn-edit").click(function(event) {
                event.preventDefault();
                $("#btn-edit").hide();
                $("#btn-back").hide();
                $("#btn-submit").show();
                $("#btn-cancel").show();
                $("#name").prop('disabled', false);
                $("#start").prop('disabled', false);
                $("#end").prop('disabled', false);
                $("#late").prop('disabled', false);
            });
            $('#btn-cancel').click(function(event) {
                event.preventDefault();
                $("#btn-edit").show();
                $("#btn-back").show();
                $("#btn-submit").hide();
                $("#btn-cancel").hide();
                $("#name").prop('disabled', true);
                $("#start").prop('disabled', true);
                $("#end").prop('disabled', true);
                $("#late").prop('disabled', true);
            });
        });
    </script>
@endpush
