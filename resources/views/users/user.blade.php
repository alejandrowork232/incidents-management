@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #121212;
            /* Fondo oscuro */
        }

        .card-custom {
            background-color: #1E1E1E;
            border: none;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 191, 255, 0.15);
            /* Azul del logo */
        }

        .card-header-custom {
            background-color: transparent;
            color: #00BFFF;
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-label {
            color: #e0e0e0;
        }

        .form-control {
            background-color: #2C2C2C;
            color: #fff;
            border: 1px solid #444;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #00BFFF;
            box-shadow: 0 0 0 0.2rem rgba(0, 191, 255, 0.25);
            background-color: #2C2C2C;
        }

        .btn-primary {
            background-color: #00BFFF;
            border-color: #00BFFF;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #009fd1;
            border-color: #009fd1;
        }

        .invalid-feedback {
            color: #f44336;
        }

        .form-control,
        .form-control option,
        select,
        select option {
            color: #ffffff !important;
            background-color: #2C2C2C !important;
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <i class="bi bi-person-plus-fill me-2"></i> Create New User
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf

                            {{-- Name --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>

                            {{-- Role --}}
                            <div class="mb-4">
                                <label for="role" class="form-label">{{ __('Role') }}</label>
                                <select id="role" class="form-control @error('role') is-invalid @enderror"
                                    name="role" required>
                                    <option value="usuario" {{ old('role') == 'usuario' ? 'selected' : '' }}>Usuario
                                    </option>
                                    <option value="soporte" {{ old('role') == 'soporte' ? 'selected' : '' }}>Soporte
                                    </option>
                                    <option value="administrador" {{ old('role') == 'administrador' ? 'selected' : '' }}>
                                        Administrador</option>
                                </select>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
