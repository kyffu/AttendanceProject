@extends('layouts.app')
@section('title', 'Shift Management')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Pengaturan Waktu Shift</h5>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('shift.create') }}" class="btn btn-primary ">Tambah Shift</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Shift</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($shifts as $shift)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>{{ $shift->name }}</td>
                                    <td>{{ $shift->start_time }}</td>
                                    <td>{{ $shift->end_time }}</td>
                                    <td><a class="footer-link me-4"
                                            href="{{ route('shift.detail', Crypt::encryptString($shift->id)) }}"><i
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
