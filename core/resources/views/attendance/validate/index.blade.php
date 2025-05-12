@extends('layouts.app')
@section('title', 'Validasi Presensi')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Validasi Presensi</h5>

            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th>Nama</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $user->name }}</span>
                                    </td>
                                    <td>
                                        <a class="footer-link me-4" href="{{route('attendance.validate.detail', Crypt::encryptString($user->id))}}"><i
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
