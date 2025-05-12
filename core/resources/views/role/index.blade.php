@extends('layouts.app')
@section('title', 'Role Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Role Management</h5>
                <div class="d-flex justify-content-end">
                     
                    <a href="{{ route('role.create') }}" class="btn btn-primary ">Add role</a>
                     
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Slug</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($roles as $role)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $role->name }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $role->slug }}</span>
                                    </td>
                                    <td class="d-flex">
                                        <a class="btn btn-sm btn-icon btn-primary me-1" title="Edit"
                                        href="{{ route('role.detail', Crypt::encryptString($role->id)) }}">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>

                                        <form action="{{ route('role.destroy', $role->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-icon btn-danger"
                                                title="Delete"
                                                {{ !$role->can_delete ? 'disabled' : '' }}>
                                                <i class="bx bx-trash-alt"></i>
                                            </button>
                                        </form>

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
