@extends('layouts.app')
@section('title', 'Create User')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Add User</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.store') }}" id="form-user">
                            @method('POST')
                            @csrf
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Please enter your name" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Please enter your e-mail" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-company">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="passworda" id="basic-default-company"
                                        placeholder="Please enter your password" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-email">Confirmation
                                    Password</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <input type="password" class="form-control" name="passwordb"
                                            placeholder="Please re-confirm yout password" autocomplete="off" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-email">Shift</label>
                                <div class="col-sm-10">
                                    <select name="shift" id="shift" class="form-select">
                                        <option value="" selected disabled>Pilih Shift Karyawan</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ Crypt::encryptString($shift->id) }}">{{ $shift->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Posisi Pekerjaan</label>
                                <div class="col-sm-10">
                                    <select name="position" id="position" class="form-select">
                                        <option value="" selected disabled>Pilih Posisi Karyawan</option>
                                        @foreach ($positions as $position)
                                            <option value="{{ Crypt::encryptString($position->id) }}" data-role-id="{{ $position->role_id }}" data-role-name="{{ $position->name }}">
                                                {{ $position->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Role Jabatan</label>
                                <div class="col-sm-10">
                                    <select name="roles" id="role" class="form-select" disabled>
                                        <option value="{{ Crypt::encryptString($position->role_id) }}">
                                            {{ 'Pilih Posisi terlebih dahulu' ?? $position->name  }}
                                        </option>
                                    </select>
                                    {{-- <input type="hidden" name="roles" value="{{ Crypt::encryptString($position->role_id) }}"> --}}
                                    <input type="hidden" id="roles-hidden" name="roles" value="{{ Crypt::encryptString($position->role_id) }}">
                                </div>
                            </div>
                            <div class="row mb-3" id="row-mandor" hidden>
                                <label class="col-sm-2 col-form-label" for="parent">Mandor/ Supervisor</label>
                                <div class="col-sm-10">
                                    <select name="parent" id="parent" class="form-select">
                                        <option value="" selected disabled>Pilih Mandor/ Supervisor Karyawan</option>
                                        @foreach ($parents as $parent)
                                            <option value="{{ Crypt::encryptString($parent->id) }}" data-slug="{{ $parent->slug }}">{{ $parent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Send</button>
                                <button type="reset" class="btn btn-secondary me-1 mb-1">Reset</button>
                                <a href="{{ route('user.index') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Basic with Icons -->
        </div>
    </div>
@stop

@push('scripts')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const positionSelect = document.getElementById('position');
        const roleSelect = document.getElementById('role');
        const parentSelect = document.getElementById('parent');
        const rolesHidden = document.getElementById('roles-hidden');
        const form = document.getElementById('form-user');
        const mandor = document.getElementById('row-mandor');

        // Simpan semua option parent secara statis di awal
        const allParentOptions = Array.from(parentSelect.querySelectorAll('option'));

        positionSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const roleId = selectedOption.getAttribute('data-role-id');
            const roleName = selectedOption.getAttribute('data-role-name');

            // Update Role Jabatan
            roleSelect.innerHTML = `<option value="${roleId}">${roleName}</option>`;
            rolesHidden.value = roleId;

            // Tentukan slug berdasarkan role name
            let filteredSlug = '';
            if (roleName.toLowerCase() === 'karyawan') {
                filteredSlug = 'spv';
                mandor.hidden = false
            } else if (roleName.toLowerCase() === 'tukang') {
                filteredSlug = 'mandor';
                mandor.hidden = false
            } else {
                mandor.hidden = true
            }

            // Selalu mulai dari semua option original
            let optionsHtml = '<option value="" selected disabled>Pilih Mandor/ Supervisor Karyawan</option>';
            allParentOptions.forEach(option => {
                const slug = option.getAttribute('data-slug');
                if (filteredSlug && slug === filteredSlug) {
                    optionsHtml += `<option value="${option.value}">${option.textContent}</option>`;
                }
            });
            parentSelect.innerHTML = optionsHtml;
        });

        form.addEventListener('reset', function() {
            roleSelect.innerHTML = '<option value="">Pilih Posisi terlebih dahulu</option>';
            roleSelect.disabled = true;
            rolesHidden.value = '';
            mandor.hidden(true)

            // Reset ke semua option parent
            parentSelect.innerHTML = '<option value="" selected disabled>Pilih Mandor/ Supervisor Karyawan</option>';
            allParentOptions.forEach(option => {
                if (option.value) {
                    parentSelect.innerHTML += `<option value="${option.value}" data-slug="${option.getAttribute('data-slug')}">${option.textContent}</option>`;
                }
            });
        });
    });

</script>
@endpush

