@extends('layouts.app')
@section('title', 'Tambah Posisi Kerja')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Tambah Posisi Kerja</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('settings.position.store') }}">
                            @method('POST')
                            @csrf
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Judul Posisi Kerja</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title"
                                        placeholder="Mohon masukkan judul posisi kerja" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Deskripsi Posisi Kerja</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="description"
                                        placeholder="Mohon masukkan deskripsi posisi kerja" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Tarif Gaji</label>
                                <div class="col-sm-10">
                                    <select name="salaries" id="salary" class="form-select">
                                        <option value="" selected disabled>Pilih Tarif gaji</option>
                                        @foreach ($salaries as $salary)
                                            <option value="{{Crypt::encryptString($salary->id)}}">{{$salary->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Role Jabatan</label>
                                <div class="col-sm-10">
                                    <select name="roles" id="roles" class="form-select">
                                        <option value="" selected disabled>Pilih Role Jabatan</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ Crypt::encryptString($role->id) }}">{{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                <button type="reset" class="btn btn-secondary me-1 mb-1">Reset</button>
                                <a href="{{ route('settings.position.index') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
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

        });
    </script>
@endpush
