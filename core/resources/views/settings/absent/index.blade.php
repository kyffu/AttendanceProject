@extends('layouts.app')
@section('title', 'Master Absen')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Jenis Ketidakhadiran</h5>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('settings.absent.create') }}" class="btn btn-primary ">Tambah Data</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th>Jenis Ketidakhadiran</th>
                                <th>Kuota</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($masters as $master)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $master->name }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $master->quota }} kali</span>
                                    </td>
                                    <td>
                                        <a class="footer-link me-4" href="{{route('settings.absent.detail', Crypt::encryptString($master->id))}}"><i
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
