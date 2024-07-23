@extends('layouts.app')

@section('content')

@include('layouts.header')

<div class="row justify-content-center position-relative" style="top: 50px;">
    <div class="col-md-10">
        <div class="card shadow-sm p-4">

            <h2 class="text-center">Enter Client Details</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form id="attendanceForm" class="row needs-validation" novalidate
                action="{{route('admin.addclientpost')}}" method="POST">
                @csrf
                <div class="form-row d-flex mb-3 gap-2">
                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label" for="client_name">Add Client<span class="text-danger">*</span></label>
                        <input type="text" id="client_name" name="client_name" class="form-control"
                            placeholder="Enter Client Name" value="{{ old('client_name') }}" required>
                        @error('client_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="form-group col-md-3  mb-3">
                        <button type="submit" class="btn btn-primary add btn-block">Add</button>
                    </div>
                </div>
            </form>

            <table></table>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .btn-block {
            width: 100%;
        }
        .add{
            position: relative;
            top: 5vh;
        }
    </style>

@endpush

@push('scripts')

@endpush

@endsection