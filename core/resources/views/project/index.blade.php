@extends('layouts.app')
@section('title', 'Daftar Proyek')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Data Proyek</h5>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('project.create') }}" class="btn btn-primary ">Tambah</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th>Nama Proyek</th>
                                <th>Mandor</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $i = 1;
                                $statuses = [
                                    0 => ['label' => 'Terdaftar', 'class' => 'bg-primary'],
                                    1 => ['label' => 'Terkunci', 'class' => 'bg-warning'],
                                    2 => ['label' => 'Selesai', 'class' => 'bg-dark'],
                                    3 => ['label' => 'Tunggu Validasi', 'class' => 'bg-info'],
                                    4 => ['label' => 'Disetujui', 'class' => 'bg-success'],
                                    5 => ['label' => 'Ditolak', 'class' => 'bg-danger'],
                                ];
                            @endphp
                            @foreach ($projects as $project)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $project->name }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $project->foreman->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statuses[$project->status]['class'] }}">{{ $statuses[$project->status]['label'] }}</span>
                                    </td>
                                    <td>
                                        <a class="footer-link me-4" href="{{route('project.detail',Crypt::encryptString($project->id))}}"><i class="bx bx-show me-2"></i>
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
