@extends('layouts.app')
@section('title', 'Detail Pengajuan')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Pengajuan Ketidakhadiran</h5>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td width="15%">Jenis Ketidakhadiran</td>
                                <td width="1%">:</td>
                                <td>{{ $absent->master->name }}</td>
                            </tr>
                            <tr>
                                <td width="15%">Tanggal Mulai</td>
                                <td width="1%">:</td>
                                <td>{{ $absent->start_date }}</td>
                            </tr>
                            <tr>
                                <td width="15%">Tanggal Selesai</td>
                                <td width="1%">:</td>
                                <td>{{ $absent->end_date }}</td>
                            </tr>
                            <tr>
                                <td width="15%">Status</td>
                                <td width="1%">:</td>
                                <td><span
                                        class="badge bg-label-{{ $absent->status == 0 ? 'secondary' : ($absent->status == 1 ? 'primary' : 'danger') }} me-1">
                                        {{ $absent->status == 0 ? 'Belum Disetujui' : ($absent->status == 1 ? 'Disetujui' : 'Ditolak') }}
                                </td>
                            </tr>
                            <tr>
                                <td width="15%">Diajukan oleh</td>
                                <td width="1%">:</td>
                                <td>{{ $absent->user_created->name }}</td>
                            </tr>
                            <tr>
                                @php
                                    $totalQuota = $absent->master->quota - $quota;
                                @endphp
                                <td width="15%">Sisa Kuota Pengajuan</td>
                                <td width="1%">:</td>
                                <td><span
                                        class="badge bg-label-{{ $totalQuota == 0 ? 'danger' : 'info' }} me-1">{{ $totalQuota }}
                                        kali</span></td>
                            </tr>
                            @if ($absent->validated_by)
                                <tr>
                                    <td width="15%">Telah disetujui oleh</td>
                                    <td width="1%">:</td>
                                    <td>{{ $absent->validated_by ? $absent->user_validated->name : '' }}</td>
                                </tr>
                            @endif
                            @if ($absent->notes)
                                <tr>
                                    <td width="15%">Keterangan</td>
                                    <td width="1%">:</td>
                                    <td>{{ $absent->notes ? $absent->notes : '' }}</td>
                                </tr>
                            @endif
                        </table>
                        <br>
                        @if ($absent->evidence_file)
                            <div class="mb-3" id="col-img">
                                <div class="mb-4">
                                    <label for="formFile" class="form-label">Bukti Ketidakhadiran</label>
                                    <img src="{{ asset($absent->evidence_file) }}" class="img-thumbnail mb-2"
                                        style="max-width: 20%; height: auto;" id="photoOut" data-bs-toggle="modal"
                                        data-bs-target="#imageModal">
                                    <div class="form-text">Klik gambar untuk memperbesar</div>
                                </div>
                            </div>
                        @endif
                        @role(['spv','mandor','superadmin'])
                        <div class="mt-2">
                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                data-bs-target="#absentModal">Validasi</button>

                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary" id="btn-back">Kembali</a>
                        </div>
                        @endrole
                    </div>
                    <!-- /Account -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Bukti Ketidakhadiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ asset($absent->evidence_file) }}" id="modalImage" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="absentModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Validasi Ketidakhadiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('attendance.absent.validate') }}" method="POST">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="absence" value="{{ Crypt::encryptString($absent->id) }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="form-label" for="basic-default-name">Beri Keterangan</label>
                                <textarea class="form-control" name="notes" rows="3">{{ $absent->notes }}</textarea>
                                <div class="form-text">Keterangan tidak wajib diisi</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="form-label" for="basic-default-name">Validasi Pengajuan <sup
                                        style="color: red;">*</sup></label>
                                <select name="validation" class="form-select" required>
                                    <option value="" selected disabled>Pilih Status Validasi</option>
                                    <option value="{{ Crypt::encryptString(1) }}"
                                        {{ $absent->status == 1 ? 'selected' : '' }}>Setujui</option>
                                    <option value="{{ Crypt::encryptString(2) }}"
                                        {{ $absent->status == 2 ? 'selected' : '' }}>Tolak</option>
                                </select>
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
