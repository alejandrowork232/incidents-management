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
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Create New Incident
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('incidents.store') }}">
                            @csrf

                            {{-- Title --}}
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input id="title" type="text"
                                    class="form-control @error('title') is-invalid @enderror"
                                    name="title" value="{{ old('title') }}" required autofocus>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                    name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Category --}}
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select id="category" class="form-control @error('category') is-invalid @enderror"
                                    name="category" required>
                                    <option value="hardware" {{ old('category') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                                    <option value="software" {{ old('category') == 'software' ? 'selected' : '' }}>Software</option>
                                    <option value="network" {{ old('category') == 'network' ? 'selected' : '' }}>Network</option>
                                    <option value="security" {{ old('category') == 'security' ? 'selected' : '' }}>Security</option>
                                </select>
                                @error('category')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Urgency --}}
                            <div class="mb-3">
                                <label for="urgency" class="form-label">Urgency</label>
                                <select id="urgency" class="form-control @error('urgency') is-invalid @enderror"
                                    name="urgency" required>
                                    <option value="low" {{ old('urgency') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('urgency') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('urgency') == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('urgency')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Impact --}}
                            <div class="mb-4">
                                <label for="impact" class="form-label">Impact</label>
                                <select id="impact" class="form-control @error('impact') is-invalid @enderror"
                                    name="impact" required>
                                    <option value="low" {{ old('impact') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('impact') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('impact') == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('impact')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    Create Incident
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
