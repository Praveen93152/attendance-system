@extends('layouts.app')

@section('content')

@include('layouts.header')
<h2 class="text-center">Admin Panel</h2>

<form id="adminSearchForm" class="row" method="POST">
    @csrf
    <div class="row mb-4">
        <!-- First row with three columns -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="admin_Client">Client</label>
                <select id="admin_Client" name="admin_Client[]" class="form-control selectpicker" multiple
                    data-live-search="true" data-actions-box="true">
                    <option value="">Select Client</option>
                    <option value="Fusion Microfinance" {{ in_array('Fusion Microfinance', old('admin_Client', [])) ? 'selected' : '' }}>Fusion Microfinance</option>
                    <option value="Sindhuja Finance" {{ in_array('Sindhuja Finance', old('admin_Client', [])) ? 'selected' : '' }}>Sindhuja Finance</option>
                    <option value="Swara Fincare" {{ in_array('Swara Fincare', old('admin_Client', [])) ? 'selected' : '' }}>Swara Fincare</option>
                    <option value="Seeds Fincap" {{ in_array('Seeds Fincap', old('admin_Client', [])) ? 'selected' : '' }}>Seeds Fincap</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="admin_state">State</label>
                <select id="admin_state" name="admin_state[]" class="form-control selectpicker" multiple
                    data-live-search="true" data-actions-box="true">
                    <option value="">Select State</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->state }}">{{ $state->state }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="admin_branch">Branch</label>
                <select id="admin_branch" name="admin_branch[]" class="form-control selectpicker" multiple
                    data-live-search="true" data-actions-box="true">
                    <option value="">Select Branch</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch }}</option>
                    @endforeach
                    <option value="Field">Field</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <!-- Second row with three columns -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="emp_code">Employee code</label>
                <input type="text" id="emp_code" name="emp_code" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="emp_mobile">Employee mobile No</label>
                <input type="text" id="emp_mobile" name="emp_mobile" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="emp_name">Employee Name</label>
                <input type="text" id="emp_name" name="emp_name" class="form-control">
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <!-- Third row with two columns -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="from_date">From Date</label>
                <input type="date" id="from_date" name="from_date" class="form-control" max="{{ date('Y-m-d') }}"
                    value="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="to_date">To Date</label>
                <input type="date" id="to_date" name="to_date" class="form-control" max="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" id="adminSearchFormButton" class="btn btn-primary w-100">Search</button>
        </div>
    </div>
</form>

<div id="adminResult" class="mt-3">
    <!-- <div class="row">
        @forelse($results as $result)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="{{ asset($result->path) }}" class="card-img-top" alt="Image">
                    <div class="card-body">
                        <h5 class="card-title">Client: {{ $result->client }}</h5>
                        <p class="card-text">State: {{ $result->state }}</p>
                        <p class="card-text">Branch: {{ $result->branch }}</p>
                        <p class="card-text">Uploaded by: {{ $result->user->employee_name ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center">No results found.</p>
            </div>
        @endforelse
    </div> -->
</div>

@endsection

@push('styles')
    <style>
        .btn-block {
            width: 100%;
        }

        p {
            margin: 0px;
        }

        .download-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .download-all-btn {
            margin-bottom: 10px;
        }
        .total{
            font-size: 24px;
            color: green;
        }
    </style>
@endpush

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"
        rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#adminSearchFormButton').click(function (e) {
                e.preventDefault();
                var formData = $('#adminSearchForm').serialize();

                $.ajax({
                    url: '{{ route("admin.search") }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function () {
                        $('#adminResult').empty();
                    },
                    success: function (data) {
                        if (data.results.length > 0) {
                            var resultsHtml = '<button id="downloadAllBtn" class="btn btn-success download-all-btn">Download All</button>';
                            resultsHtml+='<div class="total">Total Photes:'+data.results.length+'</div>';
                            resultsHtml += '<div class="row">';
                            
                            $.each(data.results, function (index, result) {
                                resultsHtml += '<div class="col-md-6 mb-4">' +
                                    '<div class="card">' +
                                    '<img src="' + result.path + '" class="card-img-top" alt="Image">' +
                                    '<button class="download-btn" data-path="' + result.path + '">Download</button>' +
                                    '<div class="card-body">' +
                                    '<h5 class="card-text">Client: ' + result.client + '</h5>' +
                                    '<p class="card-text">State: ' + result.state + '</p>' +
                                    '<p class="card-text">Branch: ' + result.branch + '</p>' +
                                    '<p class="card-text">Address: ' + result.address + '</p>' +
                                    '<p class="card-text">Uploaded by: ' + result.employee_name + '</p>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                            });
                            resultsHtml += '</div>';
                            $('#adminResult').html(resultsHtml);

                            // Download individual image
                            $('.download-btn').click(function() {
                                var path = $(this).data('path');
                                window.location.href = '{{ route("download.image") }}?path=' + encodeURIComponent(path);
                            });

                            // Download all images
                            $('#downloadAllBtn').click(function() {
                                var paths = data.results.map(result => result.path);
                                window.location.href = '{{ route("download.allImages") }}?paths=' + encodeURIComponent(JSON.stringify(paths));
                            });

                        } else {
                            $('#adminResult').html('<div class="col-12"><p class="text-center">No results found.</p></div>');
                        }
                    },
                    error: function () {
                        $('#adminResult').html('<div class="col-12"><p class="text-center">An error occurred while fetching the results.</p></div>');
                    }
                });
            });
        });
    </script>
@endpush
