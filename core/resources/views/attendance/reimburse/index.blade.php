@extends('layouts.app')
@section('title', 'Data Reimburs')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Daftar Reimburse</h5>
                @role(['karyawan','superadmin'])
                <div class="d-flex justify-content-end">
                    <a href="{{ route('attendance.reimburse.create') }}" class="btn btn-primary ">Tambah</a>
                </div>
                @endrole
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th>Tgl. Reimburs</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Pemohon</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($data as $item)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $item->reimbursement_date }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $item->description }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{$item->status == 'pending' ? 'secondary' : ($item->status == 'validated' ? 'primary' : 'danger') }} me-1">
                                            {{strtoupper($item->status) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $item->name }}</span>
                                    </td>
                                    <td>
                                        <a class="footer-link me-4" href="{{route('attendance.reimburse.detail', Crypt::encryptString($item->id))}}"><i
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
