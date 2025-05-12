@extends('layouts.app')
@section('title', 'User Management')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="https://ui-avatars.com/api/?bold=true&background=random&color=ffffff&?rounded=true&name={{ $user->name }}"
                             alt="user-avatar" class="d-block rounded" height="100" width="100" />
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <form action="{{ route('user.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="uuid" value="{{ Crypt::encryptString($user->id) }}" />
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input class="form-control" type="text" name="name" id="name"
                                       value="{{ $user->name }}" placeholder="Please enter your full name" disabled required />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-select" id="status" disabled required>
                                    <option value="1" {{ $user->status == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $user->status == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <input type="hidden" name="status" value="{{ $user->status }}">

                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="email" name="email" id="email"
                                       value="{{ $user->email }}" placeholder="Please enter your e-mail"
                                       autocomplete="off" disabled required />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="******" disabled>
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalCenter">Change Password</button>
                                </div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Shift</label>
                                <select name="shift" id="shift" class="form-select" disabled required>
                                    <option value="" selected disabled>Pilih Shift Karyawan</option>
                                    @foreach ($shifts as $shift)
                                        <option value="{{ Crypt::encryptString($shift->id) }}" {{ $user->shift_id == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="shift" value="{{ Crypt::encryptString($user->shift_id) }}">

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Posisi Pekerjaan</label>
                                <select name="position" id="position" class="form-select" disabled required>
                                    <option value="" selected disabled>Pilih Posisi Karyawan</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ Crypt::encryptString($position->id) }}"
                                            {{ $user->position_id == $position->id ? 'selected' : '' }}
                                            data-role-id="{{ $position->role_id }}"
                                            data-role-name="{{ $position->name }}">
                                            {{ $position->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <input type="hidden" name="position" value="{{ Crypt::encryptString($user->position_id) }}" id="position-hidden"> --}}

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Role Pengguna</label>
                                <select name="roles" id="role" class="form-select" disabled>
                                    @foreach ($roles as $role)
                                        <option value="{{ Crypt::encryptString($role->id) }}"
                                            {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="roles-hidden" name="roles" value="{{ Crypt::encryptString($user->role_id) }}">
                            </div>
                        </div>
                        <div class="mt-2">
                             @role(['superadmin', 'admin'])
                            <button type="button" id="btn-edit" class="btn btn-primary me-2">Edit</button>
                            <button type="submit" id="btn-submit" style="display: none;" class="btn btn-success me-2">Save changes</button>
                            <button type="button" id="btn-cancel" style="display: none;" class="btn btn-outline-danger me-2">Cancel</button>
                            @endrole
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary" id="btn-back">Back</a>
                        </div>
                    </form>
                </div>
            </div>

            @role(['superadmin', 'admin'])
            <div class="card-body">
                <div class="mb-3 col-12 mb-0">
                    <div class="alert alert-danger">
                        <h6 class="alert-heading mb-1">Are you sure you want to delete <strong>this</strong> account?</h6>
                        <p class="mb-0">Once you delete <strong>this</strong> account, there is no going back. Please be certain.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('user.destroy', Crypt::encryptString($user->id)) }}">
                    @csrf
                    @method('DELETE')
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="confirmation"
                            @if (Session::has('autofocus')) autofocus="on" @endif />
                        <label class="form-check-label" for="accountActivation" @if (Session::has('autofocus')) style="color:red;" @endif>
                            I confirm this account deactivation
                        </label>
                    </div>
                    <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
                </form>
            </div>
            @endrole
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.updatepass', Crypt::encryptString($user->id)) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="passworda" class="form-control" autocomplete="off" placeholder="Please enter your new password!">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="passwordb" class="form-control" autocomplete="off" placeholder="Please confirm your new password!">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script type="text/javascript">
    document.getElementById('position').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const roleId = selectedOption.getAttribute('data-role-id');
        const roleName = selectedOption.getAttribute('data-role-name');
        document.getElementById('roles-hidden').value = roleId;
        const roleSelect = document.getElementById('role');
        roleSelect.innerHTML = `<option value="${roleId}">${roleName}</option>`;
    });

    $(document).ready(function () {
        $("#btn-edit").click(function (event) {
            event.preventDefault();
            $("#btn-edit").hide();
            $("#btn-back").hide();
            $("#btn-submit").show();
            $("#btn-cancel").show();
            
            $("#name, #email, #status, #shift, #position").prop('disabled', false);
        });

        $("#btn-cancel").click(function (event) {
            event.preventDefault();
            $("#btn-edit").show();
            $("#btn-back").show();
            $("#btn-submit, #btn-cancel").hide();
            $("#name, #email, #status, #shift, #position").prop('disabled', true);
        });
    });
</script>
@endpush
