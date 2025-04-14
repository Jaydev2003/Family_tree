@extends('layout.treeLayout')

@section('content')

    <link rel="stylesheet" href="{{ asset('tree/form.css') }}">

    <div id="main" class="main">

        <div class="container">
            <h2 class="form-heading text-center">Add Family Member</h2>
            <form action="{{ Route('store') }}" class="parent-form" method="POST">
                @csrf
                <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="name" class="form-label ">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="name">
                    @error('name')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="email">
                    @error('email')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

                <div class="row">

                    <div class="mb-3 col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select Status</option>
                            <option value="Married" {{ old('status') == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Unmarried" {{ old('status') == 'Unmarried' ? 'selected' : '' }}>Un-married</option>
                        </select>
                        <span>
                            @error('status')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </span>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        <span>
                            @error('gender')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </span>
                    </div>

                    {{-- <div class="mb-3 col-md-4">
                        <label for="relation" class="form-label">Relation</label>
                        <select name="relation" id="relation" class="form-control">
                            <option value="" disabled {{ old('relation') ? '' : 'selected' }}>Select relation</option>
                            <option value="father" {{ old('relation')=='father' ? 'selected' : '' }}>Father</option>
                            <option value="mother" {{ old('relation')=='mother' ? 'selected' : '' }}>Mother</option>
                            <option value="brother" {{ old('relation')=='brother' ? 'selected' : '' }}>Brother</option>
                            <option value="sister" {{ old('relation')=='sister' ? 'selected' : '' }}>Sister</option>
                            <option value="son" {{ old('relation')=='son' ? 'selected' : '' }}>Son</option>
                            <option value="daughter" {{ old('relation')=='daughter' ? 'selected' : '' }}>Daughter</option>
                            <option value="wife" {{ old('relation')=='wife' ? 'selected' : '' }}>Wife</option>
                        </select>
                        <span>
                            @error('relation')
                            <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </span>
                    </div> --}}
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">Mobile Number</label>
                            <input type="tel" class="form-control" value="{{old('phone')}}" name="phone" id="phone"
                                aria-describedby="phoneHelp">
                            <span>
                                @error('phone')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" value="{{old('address')}}" class="form-control" name="address" id="address"
                                aria-describedby="addressHelp">
                            <span>
                                @error('address')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </span>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="text-center col-md-6">
                        <button type="submit" class="btn-form w-100">Submit</button>
                    </div>
                    <div class="text-center col-md-6">
                        <a href="{{route('list')}}" class="btn-form w-100">Cancel</a>
                    </div>
                </div>

            </form>
        </div>
    </div>

@endsection