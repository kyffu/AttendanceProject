@extends('layouts.app')
@section('title', 'Presensi')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header">
                <h5>Presensi Karyawan</h5>
                <div class="alert alert-danger" role="alert">
                    PRESENSI {{$check? 'KELUAR' : 'MASUK'}}
                </div>
            </div>
            <div class="card-body">
                <video id="video" width="854" height="480" autoplay></video>
                <br />
                <button id="snap" class="btn btn-primary">Ambil Gambar</button>
                <canvas id="canvas" width="854" height="854" style="display:none;"></canvas>
                <div id="attendance"></div>

                <form id="attendanceForm" method="POST" action="{{ route('attendance.store') }}">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="photo" id="photo">
                    <br />
                    <button type="submit" class="btn btn-success" id="btn-send" style="display: none;">Kirim
                        Presensi</button>
                </form>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->
    </div>
@stop
@push('scripts')
    <script>
        // Access the device camera and stream to video element
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(function(stream) {
                var video = document.getElementById('video');
                video.srcObject = stream;
                video.play();
            })
            .catch(function(err) {
                console.error("Error: " + err);
                alert("Unable to access camera. Please ensure camera access is allowed.");
            });

        // Capture a photo when the button is clicked
        document.getElementById('snap').addEventListener('click', function() {
            var canvas = document.getElementById('canvas');
            var video = document.getElementById('video');
            var context = canvas.getContext('2d');

            context.drawImage(video, 0, 0, 854, 854);

            var dataUri = canvas.toDataURL('image/jpeg');
            document.getElementById('attendance').innerHTML = '<img id="captured-img" src="' + dataUri + '"/>';
            document.getElementById('photo').value = dataUri;
            document.getElementById("btn-send").style.display = 'block';
            document.getElementById("snap").style.display = 'none';
            document.getElementById("video").style.display = 'none';
        });
    </script>
@endpush
