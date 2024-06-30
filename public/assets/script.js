$(document).ready(function() {
    $('#state').change(function() {
        var state = $(this).val();
        $.ajax({
            url: '/get-branches',
            method: 'POST',
            data: { state: state },
            success: function(response) {
                $('#branch').html(response);
            }
        });
    });

    $('#employee_code').blur(function() {
        var code = $(this).val();
        $.ajax({
            url: '/get-employee',
            method: 'POST',
            data: { code: code },
            success: function(response) {
                $('#name').val(response.name);
            }
        });
    });

    $('#attendanceForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        navigator.geolocation.getCurrentPosition(function(position) {
            formData.append('latitude', position.coords.latitude);
            formData.append('longitude', position.coords.longitude);
            $.ajax({
                url: '/submit-attendance',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#result').html(response);
                }
            });
        });
    });
    $('#adminSearchForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '/admin/search',
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#adminResult').html(response);
            }
        });
    });
});
