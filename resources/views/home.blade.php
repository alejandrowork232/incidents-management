@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <!-- Tarjeta: Total Usuarios -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-people-fill fs-1 me-3"></i>
                            <div>
                                <h5 class="card-title mb-0">Usuarios</h5>
                                <h3 class="fw-bold">{{ $totalUsers }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta: Incidencias Abiertas -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-folder2-open fs-1 me-3"></i>
                            <div>
                                <h5 class="card-title mb-0">Abiertas</h5>
                                <h3 class="fw-bold">{{ $openIncidents }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta: Pendientes -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-hourglass-split fs-1 me-3"></i>
                            <div>
                                <h5 class="card-title mb-0">Pendientes</h5>
                                <h3 class="fw-bold">{{ $pendingIncidents }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta: En Proceso -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-gear-wide fs-1 me-3"></i>
                            <div>
                                <h5 class="card-title mb-0">En Proceso</h5>
                                <h3 class="fw-bold">{{ $inProgressIncidents }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones principales -->
        <div class="row justify-content-center text-center">
            {{-- <div class="col-md-3 mb-3">
                <a href="{{ route('incidents.create') }}" class="btn btn-outline-primary btn-lg w-100">
                    Crear Incidencia
                </a> 
            </div>--}}
            {{--<div class="col-md-6 mb-3">
                <a href="{{ url('/incidents') }}" class="btn btn-outline-secondary btn-lg w-100">
                    Ver Incidencias
                </a>
            </div>
            @role('administrador')
                 <div class="col-md-6 mb-3">
                    <a href="{{ url('/users') }}" class="btn btn-outline-info btn-lg w-100">
                        Ver Usuarios
                    </a>
                </div> 
                <div class="col-md-3 mb-3">
                    <a href="{{ route('users.create') }}" class="btn btn-outline-success btn-lg w-100">
                        Crear Usuario
                    </a>
                </div>
            @endrole--}}
        </div>
    </div>
@endsection
