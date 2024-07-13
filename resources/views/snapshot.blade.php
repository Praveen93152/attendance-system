@extends('layouts.app')

@section('content')
<h2 class="text-center">Take Daily Snapshot</h2>
<form id="attendanceForm" class="row needs-validation" novalidate method="POST" action="" enctype="multipart/form-data">
    @csrf

    <div class="form-group mb-3">
        <label class="form-label" for="branch">Branch<span class="text-danger">*</span></label>
        <select id="branch" name="branch" class="form-control">
            <option value="" disabled selected>select branch</option>
            <option value="east delhi">east delhi</option>
            <option value="west delhi">west delhi</option>
            <option value="south delhi">south delhi</option>
            <option value="shadera">shadera</option>
            <option value="centerdelhi">centerdelhi</option>
            <option value="other">other</option>
        </select>
    </div>
    
    <div class="form-group mb-3 d-flex justify-content-center" id="openCamera">
        <button type="button" id="getGeolocation" class="btn btn-secondary">
            Get Location
        </button>
    </div>

    <div class="form-group mb-3 d-flex justify-content-center" id="cameraSection" style="display:none;">
        <label for="photoInput" class="btn btn-secondary">
            <i class="fa-solid fa-camera"></i> Take Photo
        </label>
        <input type="file" id="photoInput" name="photo" accept="image/*" capture="user" style="display:none;">
    </div>

    <input type="hidden" id="latitude" name="latitude">
    <input type="hidden" id="longitude" name="longitude">
    <input type="hidden" id="place" name="place">
    <input type="hidden" id="employeeId" name="employee_id" value="12345"> <!-- Replace with dynamic value -->
    <input type="hidden" id="employeeName" name="employee_name" value="John Doe"> <!-- Replace with dynamic value -->

    <canvas id="photoCanvas" style="display:none;"></canvas>
    <div id="photoPreview" class="d-flex justify-content-center"></div> <!-- To display the captured photo -->

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {

        $('#getGeolocation').on('click', function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(success, error, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }

        function success(position) {
            geolocationGranted = true;
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            $('#latitude').val(latitude);
            $('#longitude').val(longitude);

            $.getJSON(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`, function (data) {
                $('#place').val(data.display_name || 'Unknown location');
            }).fail(function () {
                console.error('Error fetching location data');
            });

            // Show the camera section after successfully getting geolocation
            $('#cameraSection').show();
            $('#getGeolocation').hide();
        }

        function error(err) {
            geolocationGranted = false;
            if (err.code == 1) {
                alert("Error: Geolocation requires HTTPS or localhost.");
            } else {
                alert(`ERROR(${err.code}): ${err.message}`);
            }
        }
    });

        $('#photoInput').on('change', function () {
            if (this.files && this.files[0]) {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = new Image();
                    img.onload = function () {
                        var canvas = document.getElementById('photoCanvas');
                        var ctx = canvas.getContext('2d');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0);

                        // Overlay text
                        var address = $('#place').val();
                        var latitude = $('#latitude').val();
                        var longitude = $('#longitude').val();
                        var employeeId = $('#employeeId').val();
                        var employeeName = $('#employeeName').val();

                        ctx.font = "20px Arial";
                        ctx.fillStyle = "white";
                        ctx.fillText(address, 10, img.height - 80);
                        ctx.fillText(`Lat: ${latitude}, Lon: ${longitude}`, 10, img.height - 50);
                        ctx.fillText(`ID: ${employeeId}`, 10, img.height - 30);
                        ctx.fillText(`Name: ${employeeName}`, 10, img.height - 10);

                        // Show the preview of the photo with overlay
                        var preview = document.getElementById('photoPreview');
                        preview.innerHTML = '';
                        var previewImg = new Image();
                        previewImg.src = canvas.toDataURL('image/png');
                        previewImg.style.maxWidth = '100%';
                        preview.appendChild(previewImg);

                        // Convert canvas to blob for submission
                        canvas.toBlob(function(blob) {
                            var formData = new FormData();
                            formData.append('photo', blob, 'photo.png');
                            formData.append('_token', $('input[name=_token]').val());
                            formData.append('branch', $('#branch').val());
                            formData.append('latitude', latitude);
                            formData.append('longitude', longitude);
                            formData.append('place', address);
                            formData.append('employee_id', employeeId);
                            formData.append('employee_name', employeeName);

                            $.ajax({
                                url: '', // Your form action URL
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function (response) {
                                    // Handle success
                                    alert('Form submitted successfully!');
                                },
                                error: function (response) {
                                    // Handle error
                                    alert('Failed to submit the form.');
                                }
                            });
                        });
                    }
                    img.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        $('#attendanceForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            if (!geolocationGranted) {
                alert("Geolocation is required to submit the form.");
                return;
            }

            // Trigger photoInput change to capture and submit the photo
            $('#photoInput').trigger('change');
        });
    });
</script>
@endpush