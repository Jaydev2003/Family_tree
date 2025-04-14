@extends('layout.treeLayout');

@section('content')
    <div id="main" class="main">
        <form action="{{ route('childstore') }}" method="POST" class="child-form">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $parentId }}">
            <input type="hidden" name="root_parent_address" value="{{ $rootParentAddress }}">

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                    @error('name')
                        <div class="error-message text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
                    @error('email')
                        <div class="error-message text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" name="phone" id="phone" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="error-message text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" id="address" value="{{ old('address') }}">
                    @error('address')
                        <div class="error-message text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" id="close-btn">Close</button>
            </div>
        </form>
    </div>
@endsection