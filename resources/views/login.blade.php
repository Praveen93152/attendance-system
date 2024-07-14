@extends('layouts.app')

@push('styles')
    <style>
        .btn-block {
            width: 100%;
        }

        .mr {
            margin-right: 5px;
            font-weight: 300;
        }
    </style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm mt-5">
            <div class="card-body">
                <h3 class="text-center mb-4">Login</h3>
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="form-group">
                        <label class="mr">Login By:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="loginBy" id="loginByMobile"
                                value="mobile" {{ old('loginBy', 'mobile') == 'mobile' ? 'checked' : '' }}>
                            <label class="form-check-label" for="loginByMobile">Mobile No</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="loginBy" id="loginByEmpId" value="emp_id"
                                {{ old('loginBy') == 'emp_id' ? 'checked' : '' }}>
                            <label class="form-check-label" for="loginByEmpId">Employee ID</label>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="inputMobileEmpId"
                            id="labelMobileEmpId">{{ old('loginBy') == 'emp_id' ? 'Employee ID' : 'Mobile No' }}</label>
                        <input type="text" class="form-control @error('identifier') is-invalid @enderror"
                            id="inputMobileEmpId" name="inputMobileEmpId"
                            placeholder="{{ old('loginBy') == 'emp_id' ? 'Enter Employee ID' : 'Enter Mobile No' }}"
                            value="{{ old('inputMobileEmpId') }}">
                        @error('identifier')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label for="inputPassword">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="inputPassword" name="password" placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mt-4">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('input[name="loginBy"]').change(function () {
                if ($(this).val() === 'mobile') {
                    $('#labelMobileEmpId').text('Mobile No');
                    $('#inputMobileEmpId').attr('placeholder', 'Enter Mobile No');
                } else {
                    $('#labelMobileEmpId').text('Employee ID');
                    $('#inputMobileEmpId').attr('placeholder', 'Enter Employee ID');
                }
            });
        });
    </script>
@endpush