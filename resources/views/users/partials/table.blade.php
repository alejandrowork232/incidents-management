@php
    dd($users);
@endphp
@forelse ($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
        <td>{{ ucfirst($user->role) }}</td>
        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
        <td>
            <div class="d-flex align-items-center justify-content-center">
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info m-1">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="m-1"
                    onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash3"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center">No hay usuarios registrados.</td>
    </tr>
@endforelse
