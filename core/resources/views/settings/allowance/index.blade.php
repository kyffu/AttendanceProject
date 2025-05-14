@extends('layouts.app')
@section('title', 'Daftar Tunjangan')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Daftar Tunjangan</h5>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('settings.allowance.create') }}" class="btn btn-primary ">Tambah Tunjangan</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Tunjangan</th>
                                <th>Kuota</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($allowances as $allowance)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $allowance->name }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium amount">{{ $allowance->amount }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $allowance->quota }} </span>
                                    </td>
                                    <td>
                                        <a class="footer-link me-4" href="{{route('settings.allowance.detail', Crypt::encryptString($allowance->id))}}"><i
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
