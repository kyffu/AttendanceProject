@extends('layouts.app')
@section('title', 'Detail Pengajuan')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Pengajuan Lembur</h5>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td width="15%">Nama Pemohon</td>
                                <td width="1%">:</td>
                                <td>{{ $overtime->user->name }}</td>
                            </tr>
                            <tr>
                                <td width="15%">Tanggal Lembur</td>
                                <td width="1%">:</td>
                                <td>{{ $overtime->date }}</td>
                            </tr>
                            <tr>
                                <td width="15%">Durasi</td>
                                <td width="1%">:</td>
                                <td class="amount">{{ $overtime->hours }} Jam</td>
                            </tr>
                            <tr>
                                <td width="15%">Status</td>
                                <td width="1%">:</td>
                                <td><span
                                        class="badge bg-label-{{ $overtime->status == 'pending' ? 'secondary' : ($overtime->status == 'approved' ? 'primary' : 'danger') }} me-1">
                                        {{ strtoupper($overtime->status) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td width="15%">Diajukan pada</td>
                                <td width="1%">:</td>
                                <td>{{ $overtime->created_at }}</td>
                            </tr>
                            @if ($overtime->validated_by && $overtime->validated_at)
                                <tr>
                                    <td width="15%">Telah disetujui oleh</td>
                                    <td width="1%">:</td>
                                    <td>{{ $overtime->validated_by ? $overtime->validator->name : '' }}</td>
                                </tr>
                                <tr>
                                    <td width="15%">Disetujui pada</td>
                                    <td width="1%">:</td>
                                    <td>{{ $overtime->validated_at ? $overtime->validated_at : '' }}</td>
                                </tr>
                                <tr>
                                    <td width="15%">Catatan</td>
                                    <td width="1%">:</td>
                                    <td>{{ $overtime->note ? $overtime->note : '-' }}</td>
                                </tr>
                            @endif
                        </table>
                        <br>
                        @role(['spv','mandor','superadmin'])
                        <div class="mt-2">
                                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                    data-bs-target="#absentModal">Validasi</button>
                             
                            <a href="{{ route('attendance.overtime.index') }}" class="btn btn-outline-secondary"
                                id="btn-back">Kembali</a>
                        </div>
                        @endrole
                    </div>
                    <!-- /Account -->
                </div>
            </div>
        </div>
    </div>
     
        <div class="modal fade" id="absentModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Validasi Lembur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('attendance.overtime.validate') }}" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="ovt" value="{{ Crypt::encryptString($overtime->id) }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Validasi Pengajuan <sup
                                            style="color: red;">*</sup></label>
                                    <select name="validation" class="form-select" required>
                                        <option value="" selected disabled>Pilih Status Validasi</option>
                                        <option value="{{ Crypt::encryptString('approved') }}"
                                            {{ $overtime->status == 'approved' ? 'selected' : '' }}>Setujui</option>
                                        <option value="{{ Crypt::encryptString('rejected') }}"
                                            {{ $overtime->status == 'rejected' ? 'selected' : '' }}>Tolak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Beri Catatan :</label>
                                    <textarea class="form-control" name="note" rows="2"></textarea>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success me-2">Validasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
     
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {});
    </script>
@endpush
