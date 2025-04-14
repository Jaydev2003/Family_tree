@extends('layout.layout')
@section('content')
    <link rel="stylesheet" href="{{ asset('tree/login.css') }}">

    <form action="{{ route('login') }}" method="POST" class="login-form">
        @csrf
        <div class="form-container">
            <h2>Login</h2>

            @if (session('message'))
                <div class="success-message">
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            <div class="input">
                <div class="inputBox">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                    <span>
                        @error('email')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </span>
                </div>

                <div class="inputBox">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" class="password-input">
                        <span class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <span>
                        @error('password')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </span>
                </div>

                <div class="inputBox">
                    <input type="submit" value="Sign In">
                </div>
            </div>
        </div>
    </form>

    <script>

        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordInput = document.querySelector('.password-input');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
@endsection