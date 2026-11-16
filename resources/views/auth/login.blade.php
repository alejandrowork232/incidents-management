@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #121212;
        }

        .card-custom {
            background-color: #1E1E1E;
            border: none;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 191, 255, 0.15);
            max-width: 400px;
            width: 100%;
        }

        .card-header-custom {
            background-color: transparent;
            color: #00BFFF;
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: none;
            text-align: center;
            padding: 1.5rem 1rem;
        }

        .form-label {
            color: #e0e0e0;
        }

        .form-control,
        .form-control option,
        select,
        select option {
            background-color: #2C2C2C;
            color: #fff;
            border: 1px solid #444;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #00BFFF;
            box-shadow: 0 0 0 0.2rem rgba(0, 191, 255, 0.25);
        }

        .btn-primary {
            background-color: #00BFFF;
            border-color: #00BFFF;
            border-radius: 50px; /* pill shape */
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #009fd1;
            border-color: #009fd1;
        }

        .invalid-feedback {
            color: #f44336;
            display: block;
            margin-top: 0.25rem;
        }

        .form-check-label {
            color: #e0e0e0;
        }

        a.link-light {
            color: #00BFFF;
            cursor: pointer;
        }

        a.link-light:hover {
            color: #009fd1;
            text-decoration: underline;
        }

        /* Centrado vertical y horizontal */
        .full-vh-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
    </style>

    <div class="container full-vh-container">
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                Iniciar Sesión
            </div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success text-white bg-success border-0">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input id="email" type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input id="password" type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" required>
                        @error('password')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a class="link-light" href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
