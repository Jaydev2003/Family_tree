@extends('layout.treeLayout')

@section('content')
    <link rel="stylesheet" href="{{ asset('tree/edit.css') }}">

    <div id="main" class="main">

        <div class="container">
            <h2 class="form-heading text-center">Update Family Member Detail</h2>
            <form action="{{ route('update', $data->id) }}" class="parent-form" method="POST">
                @method('PUT')
                @csrf

                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $data->name) }}"
                            aria-describedby="nameHelp">
                        <span>
                            @error('name')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </span>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $data->email) }}"
                            id="email" aria-describedby="emailHelp">
                        <span>
                            @error('email')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </span>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="phone" class="form-label">Mobile Number</label>
                        <input type="tel" class="form-control" name="phone" value="{{ old('phone', $data->phone) }}"
                            id="phone" aria-describedby="phoneHelp">
                        <span>
                            @error('phone')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </span>
                    </div>

                </div>

                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="" disabled {{ old('status', $data->status) ? '' : 'selected' }}>Select Status
                            </option>
                            <option value="Married" {{ old('status', $data->status) == 'Married' ? 'selected' : '' }}>Married
                            </option>
                            <option value="Unmarried" {{ old('status', $data->status) == 'Unmarried' ? 'selected' : '' }}>
                                Un-married</option>
                        </select>
                        <span>
                            @error('status')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </span>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="relation" class="form-label">Relation</label>
                        <select name="relation" id="relation" class="form-control">
                            <option value="" disabled {{ old('relation', $data->relation) ? '' : 'selected' }}>Select
                                Relation</option>
                            <option value="father" {{ old('relation', $data->relation) == 'father' ? 'selected' : '' }}>Father
                            </option>
                            <option value="mother" {{ old('relation', $data->relation) == 'mother' ? 'selected' : '' }}>Mother
                            </option>
                            <option value="brother" {{ old('relation', $data->relation) == 'brother' ? 'selected' : '' }}>
                                Brother</option>
                            <option value="sister" {{ old('relation', $data->relation) == 'sister' ? 'selected' : '' }}>Sister
                            </option>
                            <option value="son" {{ old('relation', $data->relation) == 'son' ? 'selected' : '' }}>Son</option>
                            <option value="daughter" {{ old('relation', $data->relation) == 'daughter' ? 'selected' : '' }}>
                                Daughter</option>
                            <option value="wife" {{ old('relation', $data->relation) == 'wife' ? 'selected' : '' }}>Wife
                            </option>
                        </select>
                        <span>
                            @error('relation')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </span>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="" disabled {{ old('gender', $data->gender) ? '' : 'selected' }}>Select Gender
                            </option>
                            <option value="Male" {{ old('gender', $data->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $data->gender) == 'Female' ? 'selected' : '' }}>Female
                            </option>

                        </select>
                        <span>
                            @error('gender')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" value="{{ old('address', $data->address) }}" name="address"
                            id="address" aria-describedby="addressHelp">
                        <span>
                            @error('address')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </span>
                    </div>
                </div>


                <div class="row">
                    <div class="text-center col-md-6">
                        <button type="submit" class="btn-form">Update</button>
                    </div>
                    <div class="text-center col-md-6">
                        <a href="{{route('list')}}" class="btn-form">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </script>
@endsection