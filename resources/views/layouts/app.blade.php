<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System</title>
    <link href="{{asset('bootstrap-5.3.3-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{asset('bootstrap-5.3.3-dist/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{asset('fontawesome-free-6.5.2-web/css/all.min.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    <div class="container mt-5">
        @yield('content')
    </div>

    <script src="{{ asset('assets/jquery.js') }}"></script>
    <script src="{{ asset('bootstrap-5.3.3-dist/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
    @stack('scripts')

</body>

</html>