@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Lista de Incidencias</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <a href="{{ route('incidents.create') }}" class="btn btn-success mb-3">+ Crear Incidencia</a>
        @csrf
        <table class="table table-bordered table-striped text-center">
            <form method="GET" action="{{ route('incidents.index') }}">
                <thead class="table-dark">
                    <tr>
                        <th>
                            Title <br>
                            <input type="text" name="title" value="{{ request('title') }}"
                                class="form-control form-control-sm">
                        </th>
                        <th>
                            Category <br>
                            <select name="category" class="form-select form-select-sm">
                                <option value="">-- All --</option>
                                <option value="hardware" {{ request('category') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="software" {{ request('category') == 'software' ? 'selected' : '' }}>Software</option>
                                <option value="network" {{ request('category') == 'network' ? 'selected' : '' }}>Network</option>
                                <option value="security" {{ request('category') == 'security' ? 'selected' : '' }}>Security</option>
                            </select>
                        </th>
                        <th>
                            Urgency <br>
                            <select name="urgency" class="form-select form-select-sm">
                                <option value="">-- All --</option>
                                <option value="low" {{ request('urgency') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('urgency') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('urgency') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </th>
                        <th>
                            Impact <br>
                            <select name="impact" class="form-select form-select-sm">
                                <option value="">-- All --</option>
                                <option value="low" {{ request('impact') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('impact') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('impact') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </th>
                        <th>
                            Status <br>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">-- All --</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="reopen" {{ request('status') == 'reopen' ? 'selected' : '' }}>Reopen</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </th>
                        <th>
                            Created by <br>
                            <input type="text" name="created_by" value="{{ request('created_by') }}"
                                class="form-control form-control-sm">
                        </th>
                        <th>
                            Assigned <br>
                            <input type="text" name="assigned" value="{{ request('assigned') }}"
                                class="form-control form-control-sm">
                        </th>
                        <th>
                            Date <br>
                            <input type="date" name="date" value="{{ request('date') }}"
                                class="form-control form-control-sm">
                        </th>
                        <th>
                            <div class="d-grid gap-2 mt-2">
                                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                <a href="{{ route('incidents.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                            </div>
                        </th>
                    </tr>
                </thead>
            </form>
            
            <tbody>
                @forelse ($incidents as $incident)
                    <tr>
                        <td><a href="{{ route('incidents.show', $incident->id) }}">{{ $incident->title }}</a></td>
                        @php
                            $iconMap = [
                                'low' => ['icon' => 'bi-arrow-down', 'color' => 'text-primary'],
                                'medium' => ['icon' => 'bi-dash', 'color' => 'text-warning'],
                                'high' => ['icon' => 'bi-arrow-up', 'color' => 'text-danger'],
                            ];
                        @endphp
                        <td class="text-center align-middle">
                            <span class="d-inline-flex align-items-center gap-1 {{ $incident->category }}">
                                <i class="bi {{ $incident->category }}"></i>
                                {{ strtoupper($incident->category) }}
                            </span>
                        </td>
                        <td class="text-center align-middle">
                            <span
                                class="d-inline-flex align-items-center gap-1 {{ $iconMap[$incident->urgency]['color'] }}">
                                <i class="bi {{ $iconMap[$incident->urgency]['icon'] }}"></i>
                                {{ strtoupper($incident->urgency) }}
                            </span>
                        </td>
                        <td class="text-center align-middle">
                            <span
                                class="d-inline-flex align-items-center gap-1 {{ $iconMap[$incident->impact]['color'] }}">
                                <i class="bi {{ $iconMap[$incident->impact]['icon'] }}"></i>
                                {{ strtoupper($incident->impact) }}
                            </span>
                        </td>
                        <td>{{ ucfirst($incident->status) }}</td>
                        <td>{{ $incident->creator->name ?? '' }}</td>
                        <td>
                            @if ($incident->assignee)
                                {{ $incident->assignee->name }}
                            @else
                                <span class="text-muted">Sin asignar</span>
                            @endif
                            @role('administrador','soporte')
                                <form method="POST" action="{{ route('incidents.quickAssign', $incident->id) }}"
                                    class="d-inline mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="assigned_user" class="form-select form-select-sm w-auto d-inline"
                                        onchange="this.form.submit();">
                                        <option value="">-- Reasignar --</option>
                                        @role('administrador')
                                            @foreach ($users->where('id', '!=', $incident->user_assigned) as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        @endrole
                                        @role('soporte')
                                            @foreach ($users->where('role', 'soporte')->where('id', '!=', $incident->user_assigned) as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        @endrole
                                            
                                    </select>
                                </form>
                            @endrole
                        </td>
                        <td colspan="2">{{ $incident->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No hay incidencias.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
