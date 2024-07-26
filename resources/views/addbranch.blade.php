@extends('layouts.app')

@section('content')

@include('layouts.header')

<div class="row justify-content-center position-relative" style="top: 50px;">
    <div class="col-md-10">
        <div class="card shadow-sm p-4">

            <h2 class="text-center">Enter Branch Details</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="attendanceForm" class="row needs-validation" novalidate
                action="{{ route('admin.addbranchpost') }}" method="POST">
                @csrf

                <div class="form-row d-flex mb-3 gap-2">
                    <div class="form-group col-md-4 mb-3">
                        <label for="client">Client Name<span class="text-danger">*</span></label>
                        <select id="client" name="clients" class="form-control ">
                            <option value="" disabled selected>Select Client</option>
                            <option value="Fusion Microfinance">Fusion Microfinance</option>
                            <option value="Sindhuja Finance">Sindhuja Finance</option>
                            <option value="Swara Fincare">Swara Fincare</option>
                            <option value="Seeds Fincap">Seeds Fincap</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="state">States<span class="text-danger">*</span></label>
                        <select id="state" name="state" class="form-control">
                            <option value="" disabled selected>Select State</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                            <option value="Assam">Assam</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Chhattisgarh">Chhattisgarh</option>
                            <option value="Goa">Goa</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Haryana">Haryana</option>
                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                            <option value="Jharkhand">Jharkhand</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Manipur">Manipur</option>
                            <option value="Meghalaya">Meghalaya</option>
                            <option value="Mizoram">Mizoram</option>
                            <option value="Nagaland">Nagaland</option>
                            <option value="Odisha">Odisha</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Sikkim">Sikkim</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Telangana">Telangana</option>
                            <option value="Tripura">Tripura</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="Uttarakhand">Uttarakhand</option>
                            <option value="West Bengal">West Bengal</option>
                        </select>
                        @error('state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label class="" for="branch">Branch<span class="text-danger">*</span></label>
                        <input type="text" id="branch" name="branch" class="form-control" placeholder="Enter Branch"
                            value="{{ old('branch') }}" required>
                    </div>

                </div>

                <div class="form-row d-flex mb-3 gap-2">
                    <div class="form-group col-md-4 mb-3">
                        <label for="latitude">Latitude<span class="text-danger">*</span></label>
                        <input type="text" id="latitude" name="latitude" class="form-control"
                            placeholder="Enter Latitude" value="{{ old('latitude') }}" required>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="longitude">Longitude<span class="text-danger">*</span></label>
                        <input type="text" id="longitude" name="longitude" class="form-control"
                            placeholder="Enter Longitude" value="{{ old('longitude') }}" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="card shadow-sm p-4 my-5">
            <h2 class="text-center">For Bulk Upload</h2>
            <div class="form-row d-flex gap-2">
                <div class="form-group col-md-12 d-flex align-items-center gap-2">
                    <input type="file" class="form-control">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <div class="form-row d-flex gap-2 mt-2">
                <div class="form-group col-md-12 d-flex gap-2">
                    <a href="{{route('download.Branch.sample.data')}}" class="btn btn-link">Download sample data</a>
                </div>
            </div>
        </div>

    </div>
</div>

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"
        rel="stylesheet">
    <style>
        .btn-block {
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Add any required JavaScript here
        });
    </script>
@endpush

@endsection