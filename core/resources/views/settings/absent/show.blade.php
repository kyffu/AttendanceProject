@extends('layouts.app')
@section('title', 'Detail Ketidakhadiran')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Ketidakhadiran</h5>
                    <div class="card-body">
                        <form action="{{ route('settings.absent.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="absent" value="{{ Crypt::encryptString($master->id) }}" />
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-name">Nama Jenis Ketidakhadiran</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Contoh: Cuti Melahirkan" value="{{ $master->name }}" required  disabled/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-name">Kuota per bulan</label>
                                <input type="text" class="form-control" name="quota" id="quota"
                                    placeholder="... kali" onkeypress="return mustNumber(event)" autocomplete="off"
                                    value="{{ $master->quota }}" required  disabled/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-email">Membutuhkan Bukti?</label>
                                <div class="input-group input-group-merge">
                                    <div class="form-check">
                                        <input class="form-check-input" name="evc" id="evc" type="checkbox"
                                            value="1" id="defaultCheck3" {{ $master->evc == 1 ? 'checked' : '' }} disabled>
                                        <label class="form-check-label" for="defaultCheck3"> Butuh</label>
                                    </div>
                                </div>
                                <div class="form-text">Bukti bisa berupa foto surat keterangan dokter atau surat lain yang
                                    mendukung</div>
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
                        <form method="POST" action="{{ route('settings.absent.destroy') }}">
                            <input type="hidden" name="absend" value="{{ Crypt::encryptString($master->id) }}">
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
        $(document).ready(function() {
            $("#name").prop('disabled', true);
            $("#quota").prop('disabled', true);
            $("#evc").prop('disabled', true);

            $("#btn-edit").click(function(event) {
                event.preventDefault();
                $("#btn-edit").hide();
                $("#btn-back").hide();
                $("#btn-submit").show();
                $("#btn-cancel").show();
                $("#name").prop('disabled', false);
                $("#quota").prop('disabled', false);
                $("#evc").prop('disabled', false);

            });
            $('#btn-cancel').click(function(event) {
                event.preventDefault();
                $("#btn-edit").show();
                $("#btn-back").show();
                $("#btn-submit").hide();
                $("#btn-cancel").hide();
                $("#name").prop('disabled', true);
                $("#quota").prop('disabled', true);
                $("#evc").prop('disabled', true);

            });
        });
    </script>
@endpush
