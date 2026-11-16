@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">User Information</h4>
                    </div>
                    <div class="card-body">
                        @csrf

                        <div class="mb-3">
                            <p class="mb-1 fw-semibold text-muted">Name</p>
                            <p class="ms-2">{{ $user->name }}</p>
                            <hr class="my-3">
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 fw-semibold text-muted">Email</p>
                            <p class="ms-2">{{ $user->email }}</p>
                            <hr class="my-3">
                        </div>

                        <div class="mb-4">
                            <p class="mb-1 fw-semibold text-muted">Role</p>
                            <p class="ms-2">{{ ucfirst($user->role) }}</p>
                            <hr class="my-3">
                        </div>
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <input type="hidden" name="email" id="email" value="{{ $user->email }}">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Change Password') }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
