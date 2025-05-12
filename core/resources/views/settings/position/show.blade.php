@extends('layouts.app')
@section('title', 'Detail Jabatan')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Jabatan</h5>
                    <div class="card-body">
                        <form action="{{ route('settings.position.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="uuid" value="{{ Crypt::encryptString($position->id) }}" />
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Judul Posisi Kerja</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title"
                                        placeholder="Mohon masukkan judul posisi kerja" value="{{ $position->title }}"
                                        required disabled />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Deskripsi Posisi
                                    Kerja</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="description" id="description"
                                        placeholder="Mohon masukkan deskripsi posisi kerja"
                                        value="{{ $position->description }}" required disabled />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Tarif Gaji</label>
                                <div class="col-sm-10">
                                    <select name="salaries" id="salary" class="form-select" disabled>
                                        <option value="" selected disabled>Pilih tarif gaji</option>
                                        @foreach ($salaries as $salary)
                                            <option value="{{ Crypt::encryptString($salary->id) }}"
                                                {{ $salary->id == $position->salaries_id ? 'selected' : '' }}>
                                                {{ $salary->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Role Jabatan</label>
                                <div class="col-sm-10">
                                    <select name="roles" id="role" class="form-select" disabled>
                                        <option value="" selected disabled>Pilih Role Jabatan</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ Crypt::encryptString($role->id) }}"
                                                {{ $role->id == $position->role_id ? 'selected' : '' }}>
                                                {{ $role->name }}</option>
                                        @endforeach
                                    </select>
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
                        <form method="POST"
                            action="{{ route('settings.position.destroy') }}">
                            <input type="hidden" name="pid" value="{{Crypt::encryptString($position->id)}}">
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
            $("#description").prop('disabled', true);
            $("#title").prop('disabled', true);
            $("#salary").prop('disabled', true);
            $("#role").prop('disabled', true);

            $("#btn-edit").click(function(event) {
                event.preventDefault();
                $("#btn-edit").hide();
                $("#btn-back").hide();
                $("#btn-submit").show();
                $("#btn-cancel").show();
                $("#description").prop('disabled', false);
                $("#title").prop('disabled', false);
                $("#salary").prop('disabled', false);
                $("#role").prop('disabled', false);
            });
            $('#btn-cancel').click(function(event) {
                event.preventDefault();
                $("#btn-edit").show();
                $("#btn-back").show();
                $("#btn-submit").hide();
                $("#btn-cancel").hide();
                $("#description").prop('disabled', true);
                $("#title").prop('disabled', true);
                $("#salary").prop('disabled', true);
                $("#role").prop('disabled', true);
            });
        });
    </script>
@endpush
