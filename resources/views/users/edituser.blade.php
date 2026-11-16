@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-custom">
                    <div class="card-header card-header-custom">Editar Usuario</div>

                    <div class="card-body text-white">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method("PUT")

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end form-label">
                                    {{ __('Nombre') }}
                                </label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end form-label">
                                    {{ __('Correo Electrónico') }}
                                </label>
                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email', $user->email) }}" required autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="role" class="col-md-4 col-form-label text-md-end form-label">
                                    {{ __('Rol') }}
                                </label>
                                <div class="col-md-6">
                                    <select id="role"
                                            class="form-control @error('role') is-invalid @enderror"
                                            name="role" required>
                                        <option value="usuario"
                                            {{ (old('role') ?? $user->role) == 'usuario' ? 'selected' : '' }}>
                                            Usuario
                                        </option>
                                        <option value="soporte"
                                            {{ (old('role') ?? $user->role) == 'soporte' ? 'selected' : '' }}>
                                            Soporte
                                        </option>
                                        <option value="administrador"
                                            {{ (old('role') ?? $user->role) == 'administrador' ? 'selected' : '' }}>
                                            Administrador
                                        </option>
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Editar') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div>
        </div>
    </div>
@endsection
