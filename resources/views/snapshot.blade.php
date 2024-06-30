@extends('layouts.app')

@section('content')
<h2 class="text-center">Take Daily Snapshot</h2>
<form id="attendanceForm" class="row needs-validation" novalidate action="">

    <div class="form-group mb-3">
        <label class="form-label" for="branch">Branch<span class="text-danger">*</span></label>
        <select id="branch" class="form-control">
            <option value="">Select Branch</option>
            <!-- Populate dynamically -->
        </select>
    </div>

    <!-- Camera button -->
    <div class="text-center mb-3">
        <button type="button" id="openCamera" class="btn btn-secondary">
            <i class="fa-solid fa-camera"></i>
        </button>
    </div>

    <div class="row mt-3 justify-content-center">
        <div class="col-md-6 position-relative" id="cameraSection" style="display: none;">
            <div class="video-container">
                <video id="video" autoplay></video>
                <canvas id="canvas" width="640" height="480" style="display: none;"></canvas>
                <img id="photo" style="display: none;" />
            </div>
            <div class="text-center mt-3">
                <button type="button" id="snap" class="btn btn-primary mb-2">Take Picture</button>
                <button type="button" id="retake" class="btn btn-secondary mb-2" style="display: none;">Retake Picture</button>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection

@push('styles')
<style>
    .video-container {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        /* 16:9 aspect ratio */
        background-color: #000;
    }

    video,
    img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photo = document.getElementById('photo');
        const snap = document.getElementById('snap');
        const retake = document.getElementById('retake');
        const context = canvas.getContext('2d');
        let stream;

        // Toggle camera section visibility
        $('#openCamera').on('click', function() {
            $('#cameraSection').toggle();
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(function(mediaStream) {
                    stream = mediaStream;
                    video.srcObject = stream;
                    video.play();
                })
                .catch(function(err) {
                    console.error("Error accessing webcam: " + err);
                });
        });

        // Take picture event
        snap.addEventListener('click', function(event) {
            event.preventDefault();
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const data = canvas.toDataURL('image/png');
            photo.setAttribute('src', data);
            $('#photo').show();
            $('#video').hide();
            $('#retake').show();
            $('#snap').hide();
        });

        // Retake picture event
        retake.addEventListener('click', function(event) {
            event.preventDefault();
            $('#photo').hide();
            $('#video').show();
            $('#retake').hide();
            $('#snap').show();
        });

        // Form submission event
        $('#attendanceForm').on('submit', function(event) {
            event.preventDefault();
            const data = canvas.toDataURL('image/png');

            // if (stream) {
            //     stream.getTracks().forEach(track => track.stop());
            // }
           
            // $('#cameraSection').hide();

            $.ajax({
                url: '/upload-picture',
                type: 'POST',
                data: {
                    image: data,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert('Picture uploaded successfully!');
                    // Stop all video streams
                    // if (stream) {
                    //     stream.getTracks().forEach(track => track.stop());
                    // }
                    // Hide the camera section
                    // $('#cameraSection').hide();
                },
                error: function(error) {
                    console.error('Error uploading picture:', error);
                }
            });
        });
    });
</script>
@endpush