@extends('layouts.app')
@section('title', 'Pengaturan Umum')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Pengaturan Umum</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('settings.general.update') }}">
                            @method('PUT')
                            @csrf
                            @foreach ($data as $item)
                            @php
                                $key = ucwords(str_replace('_', ' ', $item->key));
                            @endphp
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">{{$key}}</label>
                                    <input type="hidden" name="key[]" value="{{$item->key}}">
                                    <input type="text" name="value[]" class="form-control {{$item->key == 'tarif_lembur' ? 'amount' : ''}}"
                                        placeholder="{{$item->placeholder}}" value="{{$item->value}}" required>
                                </div>
                            </div>
                            @endforeach
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
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
        function mustNumber(key) {
            key = (key) ? key : window.event;
            var char = (key.which) ? key.which : key.keyCode;

            // Allow numbers (48-57) and period (46)
            if (char > 31 && (char < 48 || char > 57) && char != 46) {
                return false;
            }

            return true;
        }

        $(document).ready(function() {
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
