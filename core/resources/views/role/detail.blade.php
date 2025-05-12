@extends('layouts.app')
@section('title', 'Role Management')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Role</h5>
                    <div class="card-body">
                        <form action="{{route('role.update')}}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="uuid" value="{{ Crypt::encryptString($role->id) }}"/>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="firstName" class="form-label">Name</label>
                                    <input class="form-control" type="text" name="name" id="name"
                                        value="{{ $role->name }}" placeholder="Please enter role name" autofocus
                                        disabled required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="firstName" class="form-label">Slug</label>
                                    <input class="form-control" type="text" name="slug" id="slug"
                                        value="{{ $role->slug }}" placeholder="Please enter slug" autofocus
                                        disabled required />
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="button" id="btn-edit" class="btn btn-primary me-2">Edit</button>
                                <button type="submit" id="btn-submit" style="display: none;"
                                    class="btn btn-success me-2">Save changes</button>
                                <button type="button" id="btn-cancel" style="display: none;"
                                    class="btn btn-outline-danger me-2">Cancel</button>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary" id="btn-back">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#name").prop('disabled', true);
            $("#slug").prop('disabled', true);
            $("#btn-edit").click(function(event) {
                event.preventDefault();
                $("#btn-edit").hide();
                $("#btn-back").hide();
                $("#btn-submit").show();
                $("#btn-cancel").show();
                $("#name").prop('disabled', false);
                $("#slug").prop('disabled', false);
            });
            $('#btn-cancel').click(function(event) {
                event.preventDefault();
                $("#btn-edit").show();
                $("#btn-back").show();
                $("#btn-submit").hide();
                $("#btn-cancel").hide();
                $("#name").prop('disabled', true);
                $("#name").prop('disabled', true);
                $("#slug").prop('disabled', true);
            });
        });
    </script>
@endpush
