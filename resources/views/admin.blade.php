@extends('layouts.app')

@section('content')

@include('layouts.header')
<h2 class="text-center">Admin Panel</h2>
<form id="adminSearchForm" class="row">
    <div class="row mb-4">
        <!-- First row with three columns -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="admin_Client">Client</label>
                <select id="admin_Client" class="form-control selectpicker" multiple data-live-search="true" data-actions-box="true">
                    <option value="">Select Client</option>
                    <!-- Populate dynamically -->
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="admin_state">State</label>
                <select id="admin_state" class="form-control selectpicker" multiple data-live-search="true" data-actions-box="true">
                    <option value="">Select State</option>
                    <!-- Populate dynamically -->
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="admin_branch">Branch</label>
                <select id="admin_branch" class="form-control selectpicker" multiple data-live-search="true" data-actions-box="true">
                    <option value="">Select Branch</option>
                    <!-- Populate dynamically -->
                </select>
            </div>
        </div>

    </div>
    <div class="row mb-5">
        <!-- Second row with two columns -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="from_date">From Date</label>
                <input type="date" id="from_date" class="form-control" max="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="to_date">To Date</label>
                <input type="date" id="to_date" class="form-control" max="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>

    </div>
</form>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <img src="..." class="card-img-top" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card 1</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>

            </div>
        </div>
    </div>


    <div class="col-md-3">
        <div class="card">
            <img src="..." class="card-img-top" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card 2</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>

            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <img src="..." class="card-img-top" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card 3</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>

            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <img src="..." class="card-img-top" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card 4</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>

            </div>
        </div>
    </div>
</div>

</div>

<div id="adminResult" class="mt-3"></div>
@push('styles')
<style>
    .btn-block {
        width: 100%;
    }
</style>
@endpush
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
@endpush
@endsection