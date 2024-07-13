@extends('layouts.app')

@section('content')

@include('layouts.header')

<div class="row justify-content-center position-relative" style="top: 50px;">
    <div class="col-md-10">
        <div class="card shadow-sm p-4">

            <h2 class="text-center">Enter Agent Details</h2>
            <form id="attendanceForm" class="row needs-validation" novalidate action="{{ route('agents.store') }}"
                method="POST">
                @csrf
                <div class="form-row d-flex mb-3 gap-2">
                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label" for="employee_code">Employee Code<span class="text-danger">*</span></label>
                        <input type="text" id="employee_code" name="employee_code" class="form-control" placeholder="Enter Employee Code" required>
                    </div>

                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label" for="employee_name">Employee Name<span class="text-danger">*</span></label>
                        <input type="text" id="employee_name" name="employee_name" class="form-control" placeholder="Enter Name" required>
                    </div>

                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label" for="mobile_no">Mobile Number<span class="text-danger">*</span></label>
                        <input type="text" id="mobile_no" name="mobile_no" class="form-control" placeholder="Enter Mobile Number" required>
                    </div>
                </div>

                <div class="form-row d-flex mb-3 gap-2">
                    <div class="form-group col-md-4 mb-3">
                        <label for="client">Client Name<span class="text-danger">*</span></label>
                        <select id="client" name="clients[]" class="form-control selectpicker" multiple data-live-search="true" data-actions-box="true">
                            <option value="Fusion Microfinance">Fusion Microfinance</option>
                            <option value="Sindhuja Finance">Sindhuja Finance</option>
                            <option value="Swara Fincare">Swara Fincare</option>
                            <option value="Seeds Fincap">Seeds Fincap</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="state">States<span class="text-danger">*</span></label>
                        <select id="state" name="state" class="form-control">
                            <option value="">Select State</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label" for="branch">Branch<span class="text-danger">*</span></label>
                        <select id="branch" name="branch[]" class="form-control selectpicker" multiple data-live-search="true" data-actions-box="true">
                        </select>
                    </div>
                </div>

                <div class="form-row d-flex mb-3 gap-2">
                    <div class="form-group col-md-6 mb-3">
                        <label class="form-label" for="password">Password<span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" required>
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label class="form-label" for="password_confirmation">Confirm Password<span class="text-danger">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <div id="result"></div>

        </div>
    </div>
</div>

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" rel="stylesheet">
    <style>
        .btn-block {
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#client').change(function () {
                var clients = $(this).val();
                $.ajax({
                    url: '{{ route("getStates") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        clients: clients
                    },
                    success: function (data) {
                        var stateSelect = $('#state');
                        stateSelect.empty();
                        stateSelect.append('<option value="">Select State</option>');
                        $.each(data, function (key, value) {
                            stateSelect.append('<option value="' + value + '">' + value + '</option>');
                        });
                    }
                });
            });

            $('#state').change(function () {
                var state = $(this).val();
                var clients = $('#client').val(); // Get selected clients
                $.ajax({
                    url: '{{ route("getBranches") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        state: state,
                        clients: clients // Pass selected clients to backend
                    },
                    success: function (data) {
                        var branchSelect = $('#branch');
                        branchSelect.empty();
                        $.each(data, function (key, value) {
                            branchSelect.append('<option value="' + value + '">' + value + '</option>');
                        });
                        branchSelect.selectpicker('refresh');
                    }
                });
            });
        });
    </script>
@endpush
@endsection
