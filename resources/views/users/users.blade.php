@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Lista de Usuarios</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <a href="{{ route('users.create') }}" class="btn btn-success mb-3">+ Crear Usuario</a>
        @csrf
        <table class="table table-bordered table-striped text-center">
            <form method="GET" action="{{ route('users.index') }}">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>
                            Nombre <br>
                            <input type="text" name="name" value="{{ request('name') }}" class="form-control form-control-sm">
                        </th>
                        <th>
                            Email <br>
                            <input type="text" name="email" value="{{ request('email') }}" class="form-control form-control-sm">
                        </th>
                        <th>
                            Rol <br>
                            <select name="role" class="form-select form-select-sm">
                                <option value="">-- Todos --</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="soporte" {{ request('role') == 'soporte' ? 'selected' : '' }}>Soporte</option>
                                <option value="usuario" {{ request('role') == 'usuario' ? 'selected' : '' }}>Usuario</option>
                            </select>
                        </th>
                        <th>
                            Fecha creación <br>
                            <input type="date" name="created_at" value="{{ request('created_at') }}" class="form-control form-control-sm">
                        </th>
                        <th>
                            <div class="d-grid gap-2 mt-2">
                                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                            </div>
                        </th>
                    </tr>
                </thead>
            </form>
            
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="d-flex align-items-center text -center justify-content-center">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info m-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="m-1"
                                    onsubmit="return confirmDelete()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>

                                <script>
                                    function confirmDelete() {
                                        return confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.');
                                    }
                                </script>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $users->links() }}
        </div>
    </div>
@endsection
