@extends('layouts.app')
@section('title', 'Pengajuan Tidak Hadir')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Pengajuan Tidak Hadir</h5>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('attendance.absent.create') }}" class="btn btn-primary ">Tambah Pengajuan</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th>Jenis Ketidakhadiran</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th>Pembuat</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($absences as $absent)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $absent->master->name }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $absent->start_date }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $absent->end_date }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{$absent->status == 0 ? 'secondary' : ($absent->status == 1 ? 'primary' : 'danger') }} me-1">
                                            {{$absent->status == 0 ? 'Belum Disetujui' : ($absent->status == 1 ? 'Disetujui' : 'Ditolak') }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $absent->user_created->name }}</span>
                                    </td>
                                    <td>
                                        <a class="footer-link me-4" href="{{route('attendance.absent.detail', Crypt::encryptString($absent->id))}}"><i
                                                class="bx bx-show me-2"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->
    </div>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
