@extends('layouts.app')
@section('title', 'Ajukan Ketidakhadiran')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Ajukan Ketidakhadiran</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('attendance.absent.store') }}" enctype="multipart/form-data">
                            @method('POST')
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-name">Jenis Ketidakhadiran</label>
                                <select name="absent_type" id="absent-type" class="form-select" required>
                                    <option value="" selected disabled>Pilih Jenis Ketidakhadiran</option>
                                    @foreach ($masters as $master)
                                        <option value="{{ Crypt::encryptString($master->id) }}"
                                            data-evidence="{{ $master->evc }}">{{ $master->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3" id="col-start-date">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Tanggal Mulai</label>
                                    <input type="text" name="start_date" id="start-date" class="form-control"
                                        placeholder="Pilih tanggal mulai" disabled required>
                                </div>
                            </div>
                            <div class="mb-3" id="col-end-date">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Tanggal Selesai</label>
                                    <input type="text" name="end_date" id="end-date" class="form-control"
                                        placeholder="Pilih tanggal selesai" disabled required>
                                </div>
                            </div>
                            <div class="mb-3" id="col-evc"style="display:none;">
                                <div class="mb-4">
                                    <label for="formFile" class="form-label">Bukti Ketidakhadiran</label>
                                    <input class="form-control" type="file" name="evidence" id="evidence" accept="image/jpeg">
                                </div>
                                <div class="form-text">Gambar harus bertipe Jpeg/Jpg dan maksimal berukuran sebesar 2MB!</div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                <a href="{{ route('attendance.absent.index') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
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
            if (char > 31 && (char < 48 || char > 57)) {
                return false;
            }
            return true;
        }
        $(document).ready(function() {
            $('#start-date').prop('disabled', true);
            $('#end-date').prop('disabled', true);
            $('#col-evc').hide();
            $('#absent-type').change(function() {
                var type = $(this).val();
                if (type != "") {
                    var evi = $('option:selected').data('evidence');
                    if (evi === 1) {
                        $('#col-evc').show();
                    } else {
                        $('#col-evc').hide();
                    }
                    $('#start-date').prop('disabled', false);
                    $('#end-date').prop('disabled', false);
                }
            });
            var minimum = '{{$minimum}}';
            $('#start-date').flatpickr({
                dateFormat: "d-m-Y",
                minDate: minimum,
            });
            $('#end-date').flatpickr({
                dateFormat: "d-m-Y",
                minDate: minimum,
            });
        });
    </script>
@endpush
