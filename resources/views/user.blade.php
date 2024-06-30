@extends('layouts.app')

@section('content')
<h2 class="text-center">Enter Agent Details</h2>
<form id="attendanceForm" class="row  needs-validation" novalidate action="{{ route('agents.store') }}" method="POST">

    <div class="form-group mb-3">
        <label class="form-label" for="employee_code">Employee Code<span class="text-danger">*</span></label>
        <input type="text" id="employee_code" class="form-control" placeholder="Enter Employee Code" required>
    </div>

    <div class="form-group mb-3">
        <label class="form-label" for="employee_name">Employee Name<span class="text-danger">*</span></label>
        <input type="text" id="employee_name" class="form-control" placeholder="Enter Name" required>
    </div>

    <!-- <div class="form-group mb-3">
        <label for="client">Client Name <span class="text-danger">*</span></label>
        <select id="client" class="form-control selectpicker" multiple data-live-search="true" data-actions-box="true">
            <option value="">Select Name</option>
            
        </select>
    </div> -->

    <div class="form-group mb-3">
        <label for="state">States <span class="text-danger">*</span></label>
        <select id="state" class="form-control">
        <option value="">Select State</option>
            @foreach ($states as $state )
            <option value="{{$state}}">{{$state}}</option>
            @endforeach

            <!-- Populate dynamically -->
        </select>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="branch">Branch<span class="text-danger">*</span></label>
        <select id="branch" class="form-control selectpicker" multiple data-live-search="true" data-actions-box="true">
            
            <!-- Populate dynamically -->
        </select>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="mobile_no">Mobile Number <span class="text-danger">*</span></label>
        <input type="text" id="mobile_no" name="mobile_no" class="form-control" placeholder="Enter Mobile Number" required>
    </div>

    <div class="form-group mb-3">
        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" required>
    </div>

    <div class="form-group mb-3">
        <label class="form-label" for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<div id="result"></div>

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
    $(document).ready(function() {
        // Fetch branches based on the selected state
        $('#state').change(function() {
            var state = $(this).val();
            $.ajax({
                url: '{{ route("agents.getBranches") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    state: state
                },
                success: function(data) {
                    var branchSelect = $('#branch');
                    branchSelect.empty();
                    data.forEach(function(branch) {
                        branchSelect.append('<option value="' + branch + '">' + branch + '</option>');
                    });
                    branchSelect.selectpicker('refresh');
                }
            });
        });
    });
</script>

@endpush
@endsection