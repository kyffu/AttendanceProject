@extends('layouts.app')
@section('title', 'Detail Pengajuan')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Pengajuan Reimburse</h5>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td width="15%">Nama Pemohon</td>
                                <td width="1%">:</td>
                                <td>{{ $reimburse->user->name }}</td>
                            </tr>
                            <tr>
                                <td width="15%">Deskripsi</td>
                                <td width="1%">:</td>
                                <td>{{ $reimburse->description }}</td>
                            </tr>
                            <tr>
                                <td width="15%">Tanggal Reimburse</td>
                                <td width="1%">:</td>
                                <td>{{ $reimburse->reimbursement_date }}</td>
                            </tr>
                            <tr>
                                <td width="15%">Jumlah</td>
                                <td width="1%">:</td>
                                <td class="amount">{{ $reimburse->amount }}</td>
                            </tr>
                            <tr>
                                <td width="15%">Status</td>
                                <td width="1%">:</td>
                                <td><span
                                        class="badge bg-label-{{ $reimburse->status == 'pending' ? 'secondary' : ($reimburse->status == 'validated' ? 'primary' : 'danger') }} me-1">
                                        {{ strtoupper($reimburse->status) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td width="15%">Diajukan pada</td>
                                <td width="1%">:</td>
                                <td>{{ $reimburse->created_at }}</td>
                            </tr>
                            @if ($reimburse->validated_by && $reimburse->validated_at)
                                <tr>
                                    <td width="15%">Telah disetujui oleh</td>
                                    <td width="1%">:</td>
                                    <td>{{ $reimburse->validated_by ? $reimburse->validator->name : '' }}</td>
                                </tr>
                                <tr>
                                    <td width="15%">Disetujui pada</td>
                                    <td width="1%">:</td>
                                    <td>{{ $reimburse->validated_at ? $reimburse->validated_at : '' }}</td>
                                </tr>
                            @endif
                        </table>
                        <br>
                        <div class="mb-3" id="col-img">
                            <div class="mb-4">
                                <label for="formFile" class="form-label">Bukti Reimburse</label>
                                <img src="{{ asset($reimburse->evidence_photo) }}" class="img-thumbnail mb-2"
                                    style="max-width: 20%; height: auto;" id="photoOut" data-bs-toggle="modal"
                                    data-bs-target="#imageModal">
                                <div class="form-text">Klik gambar untuk memperbesar</div>
                            </div>
                        </div>

                        @role(['spv','superadmin'])
                        <div class="mt-2">
                                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                    data-bs-target="#absentModal">Validasi</button>
                             
                            <a href="{{ route('attendance.reimburse.index') }}" class="btn btn-outline-secondary"
                                id="btn-back">Kembali</a>
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
                    <h5 class="modal-title" id="imageModalLabel">Bukti Reimburse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ asset($reimburse->evidence_photo) }}" id="modalImage" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
     
        <div class="modal fade" id="absentModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Validasi Reimburse</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('attendance.reimburse.validate') }}" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="reimburse" value="{{ Crypt::encryptString($reimburse->id) }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Validasi Pengajuan <sup
                                            style="color: red;">*</sup></label>
                                    <select name="validation" class="form-select" required>
                                        <option value="" selected disabled>Pilih Status Validasi</option>
                                        <option value="{{ Crypt::encryptString('validated') }}"
                                            {{ $reimburse->status == 'validated' ? 'selected' : '' }}>Setujui</option>
                                        <option value="{{ Crypt::encryptString('rejected') }}"
                                            {{ $reimburse->status == 'rejected' ? 'selected' : '' }}>Tolak</option>
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
        });
    </script>
@endpush
