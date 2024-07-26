@extends('layouts.app')

@section('content')

@include('layouts.header')

<div class="row justify-content-center position-relative" style="top: 50px;">
    <div class="col-md-10">
        <div class="card shadow-sm p-4">

            <h2 class="text-center">Enter Agent Details</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form id="attendanceForm" class="row needs-validation" novalidate
                action="{{route('admin.addemployee_post')}}" method="POST">
                @csrf
                <div class="form-row d-flex mb-3 gap-2">
                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label" for="employee_code">Employee Code<span
                                class="text-danger">*</span></label>
                        <input type="text" id="employee_code" name="employee_code" class="form-control"
                            placeholder="Enter Employee Code" value="{{ old('employee_code') }}" required>
                        @error('employee_code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label" for="employee_name">Employee Name<span
                                class="text-danger">*</span></label>
                        <input type="text" id="employee_name" name="employee_name" class="form-control"
                            placeholder="Enter Name" value="{{ old('employee_name') }}" required>
                        @error('employee_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label" for="mobile_no">Mobile Number<span
                                class="text-danger">*</span></label>
                        <input type="text" id="mobile_no" name="mobile_no" class="form-control"
                            placeholder="Enter Mobile Number" value="{{ old('mobile_no') }}" required>
                        @error('mobile_no')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row d-flex mb-3 gap-2">
                    <div class="form-group col-md-4 mb-3">
                        <label for="client">Client Name<span class="text-danger">*</span></label>
                        <select id="client" name="clients[]" class="form-control selectpicker" multiple
                            data-live-search="true" data-actions-box="true">
                            <option value="Fusion Microfinance" {{ in_array('Fusion Microfinance', old('clients', [])) ? 'selected' : '' }}>Fusion Microfinance</option>
                            <option value="Sindhuja Finance" {{ in_array('Sindhuja Finance', old('clients', [])) ? 'selected' : '' }}>Sindhuja Finance</option>
                            <option value="Swara Fincare" {{ in_array('Swara Fincare', old('clients', [])) ? 'selected' : '' }}>Swara Fincare</option>
                            <option value="Seeds Fincap" {{ in_array('Seeds Fincap', old('clients', [])) ? 'selected' : '' }}>Seeds Fincap</option>
                        </select>
                        @error('clients')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="state">States<span class="text-danger">*</span></label>
                        <select id="state" name="state" class="form-control">
                            <option value="">Select State</option>
                            <!-- Populate states dynamically -->
                        </select>
                        @error('state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label" for="branch">Branch<span class="text-danger">*</span></label>
                        <select id="branch" name="branch[]" class="form-control selectpicker" multiple
                            data-live-search="true" data-actions-box="true">
                        </select>
                        @error('branch')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- <div class="form-row d-flex mb-3 gap-2">
                    <div class="form-group col-md-6 mb-3">
                        <label class="form-label" for="password">Password<span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Enter Password" required>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label class="form-label" for="password_confirmation">Confirm Password<span
                                class="text-danger">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control" placeholder="Confirm Password" required>
                        @error('password_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div> -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

        </div>

        <div class="card shadow-sm p-4 my-5">
            <h2 class="text-center">For Bulk Upload</h2>
            <form action="{{ route('upload.employee.data') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row d-flex gap-2">
                    <div class="form-group col-md-12 d-flex align-items-center gap-2">
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </form>
            <div class="form-row d-flex gap-2 mt-2">
                <div class="form-group col-md-12 d-flex gap-2">
                    <a href="{{ route('download.employee.sample.data') }}" class="btn btn-link">Download sample data</a>
                    <a href="{{ route('download.branch.codes') }}" class="btn btn-link">Download branch codes</a>
                </div>
            </div>
            @if (session('errors'))
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach (session('errors')->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
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
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {
            // Set old state value
            var oldState = '{{ old('state') }}';
            if (oldState) {
                $('#state').append('<option value="' + oldState + '" selected>' + oldState + '</option>');
            }

            // Set old branch values
            var oldBranches = {!! json_encode(old('branch', [])) !!};
            if (oldBranches.length > 0) {
                $.each(oldBranches, function (key, value) {
                    $('#branch').append('<option value="' + value + '" selected>' + value + '</option>');
                });
                $('#branch').selectpicker('refresh');
            }

            // Load states when clients change
            $('#client').change(function () {
                var clients = $(this).val();
                $.ajax({
                    url: '{{route('admin.getstates')}}',
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

                        // Set old state value again if it exists
                        if (oldState) {
                            stateSelect.val(oldState);
                        }
                    }
                });
            });

            // Load branches when state changes
            $('#state').change(function () {
                var state = $(this).val();
                var clients = $('#client').val(); // Get selected clients
                $.ajax({
                    url: '{{ route("admin.getbranchs") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        state: state,
                        clients: clients // Pass selected clients to backend
                    },
                    success: function (data) {

                        console.log(data);
                        var branchSelect = $('#branch');
                        branchSelect.empty();
                        $.each(data, function (key, value) {
                            branchSelect.append('<option value="' + key + '">' + value + '</option>');
                        });
                        // if (oldBranches.length > 0) {
                        //     $.each(oldBranches, function (key, value) {
                        //         branchSelect.find('option[value="' + key + '"]').attr('selected', 'selected');
                        //     });
                        //     branchSelect.selectpicker('refresh');
                        // }
                        branchSelect.selectpicker('refresh');
                    }
                });
            });

            // Trigger change events if old values exist
            if (oldState) {
                $('#client').trigger('change');
            }
        });
    </script>
@endpush

@endsection