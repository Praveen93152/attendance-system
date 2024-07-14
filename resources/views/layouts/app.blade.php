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
    <style>
        .strip {
            position: relative;
            width: 100vw;
            height: 10vh;
            background-color: #8bd8bd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #6ca7a0;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 0 20px;
        }

        .strip a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6ca7a0;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;

        }

        .strip a:hover {
            background-color: #5a918b;
        }


        .loader{
            display: none;
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: 999;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .loader1{
            position: absolute;
            top: 50%;
            left: 50%;
            border: 10px solid #EAF0F6;
            border-radius: 50%;
            border-top: 10px solid #8bd8bd;
            width: 50px;
            height: 50px;
            animation: spinner 2s linear infinite;
        }

        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>

    <div class="loader">
        <div class="loader1"></div>
    </div>
    <div class="strip">
        @if (Auth::check())
            <h2>Welcome {{Auth::user()->employee_name}}</h2>
            <a href="{{ route('logout') }}" class="button">Logout</a>
        @endif
    </div>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="{{ asset('assets/jquery.js') }}"></script>
    <script src="{{ asset('bootstrap-5.3.3-dist/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
    @stack('scripts')

</body>

</html>