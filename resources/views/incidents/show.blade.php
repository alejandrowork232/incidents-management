@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            @role('soporte', 'administrador')
                <div class="col-lg-8">
                    {{-- Acciones según el estado --}}
                    <div class="my-2">
                        @if ($incident->status === 'open' || $incident->status === 'reopen')
                            <form method="POST" action="{{ route('incidents.updateStatus', $incident->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="btn btn-outline-primary">Iniciar</button>
                            </form>
                        @elseif ($incident->status === 'pending')
                            <form method="POST" action="{{ route('incidents.updateStatus', $incident->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="btn btn-outline-warning me-2">Continuar</button>
                            </form>

                            <form method="POST" action="{{ route('incidents.updateStatus', $incident->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="closed">
                                <button type="submit" class="btn btn-outline-danger">Cerrar Incidencia</button>
                            </form>
                        @elseif ($incident->status === 'in_progress')
                            <form method="POST" action="{{ route('incidents.updateStatus', $incident->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="btn btn-outline-secondary me-2 text-danger">Pendiente de ...</button>
                            </form>

                            <form method="POST" action="{{ route('incidents.updateStatus', $incident->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="closed">
                                <button type="submit" class="btn btn-outline-danger">Cerrar Incidencia</button>
                            </form>
                        @elseif ($incident->status === 'closed')
                            <form method="POST" action="{{ route('incidents.updateStatus', $incident->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="reopen">
                                <button type="submit" class="btn btn-outline-success">Reabrir</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    {{-- Acciones según el estado --}}
                    <div class="my-2">
                        @role('administrador')
                            <form id="assignForm" method="POST" action="{{ route('incidents.quickAssign', $incident->id) }}"
                                class="d-inline me-2">
                                @csrf
                                @method('PATCH')
                            
                                <select name="assigned_user" class="form-select bg-opacity-0 w-auto d-inline"
                                    onchange="document.getElementById('assignForm').submit();">
                                    <option value="">-- Asignar a usuario --</option>
                                    @foreach ($users->where('id', '!=', $incident->user_assigned) as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        @endrole
                        @role('soporte')
                            <form id="assignForm" method="POST" action="{{ route('incidents.quickAssign', $incident->id) }}"
                                class="d-inline me-2">
                                @csrf
                                @method('PATCH')
                            
                                <select name="assigned_user" class="form-select bg-opacity-0 w-auto d-inline"
                                    onchange="document.getElementById('assignForm').submit();">
                                    <option value="">-- Asignar a usuario --</option>
                                    @foreach ($users->where('role', 'soporte')->where('id', '!=', $incident->user_assigned) as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        @endrole

                        {{-- Botón para asignarse a sí mismo --}}

                        @if ($incident->assignee == auth()->user())
                            <form action="{{ route('incidents.assignSelf', $incident->id) }}" method="POST" class="d-inline"
                                style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="assigned" value="">
                                <button type="submit" class="btn btn-outline-secondary text-danger">Desasignar</button>
                            </form>
                        @else
                            <form action="{{ route('incidents.assignSelf', $incident->id) }}" method="POST" class="d-inline"
                                style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="assigned" value="{{ auth()->id() }}">
                                <button type="submit" class="btn btn-outline-secondary text-danger">Asignarme</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endrole
            @role('usuario')
            @if (Auth::id() === $incident->user_creator && \Carbon\Carbon::now()->diffInDays($incident->updated_at) < 2)
                    <div class="col-lg-12">
                        <div class="my-2">
                            @if ($incident->status === 'closed')
                                <form method="POST" action="{{ route('incidents.updateStatus', $incident->id) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="reopen">
                                    <button type="submit" class="btn btn-outline-success">Reabrir</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            @endrole

            <div class="col-lg-10">

                {{-- Título y estado --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="fw-bold mb-0">{{ $incident->title }}</h2>
                    <span class="badge bg-{{ $incident->status === 'closed' ? 'secondary' : 'info' }} text-uppercase">
                        {{ str_replace('_', ' ', $incident->status) }}
                    </span>
                </div>

                {{-- Descripción --}}
                <div class="mb-4">
                    <p class="text-muted fs-5" style="white-space: pre-line;">{{ $incident->description }}</p>
                </div>

                {{-- Detalles adicionales --}}


                {{-- Cambio de estado --}}


                {{-- Adjuntos --}}


                {{-- Historial de comentarios / eventos --}}
            </div>
            <div class="col-lg-2">
                <div class="row ms-2 mb-4 text-muted">
                    @php
                        $iconMap = [
                            'low' => ['icon' => 'bi-arrow-down', 'color' => 'text-primary'],
                            'medium' => ['icon' => 'bi-dash', 'color' => 'text-warning'],
                            'high' => ['icon' => 'bi-arrow-up', 'color' => 'text-danger'],
                        ];
                    @endphp
                    <div class="col-md-12"><strong>Category:</strong>
                        <span class="d-inline-flex align-items-center gap-1 {{ $incident->category }}">
                            <i class="bi {{ $incident->category }}"></i>
                            {{ strtoupper($incident->category) }}
                        </span>
                    </div>
                    <div class="col-md-12"><strong>Urgency:</strong>
                        <span class="d-inline-flex align-items-center gap-1 {{ $iconMap[$incident->urgency]['color'] }}">
                            <i class="bi {{ $iconMap[$incident->urgency]['icon'] }}"></i>
                            {{ strtoupper($incident->urgency) }}
                        </span>
                    </div>
                    <div class="col-md-12"><strong>Impact:</strong> <span
                            class="d-inline-flex align-items-center gap-1 {{ $iconMap[$incident->impact]['color'] }}">
                            <i class="bi {{ $iconMap[$incident->impact]['icon'] }}"></i>
                            {{ strtoupper($incident->impact) }}
                        </span></div>
                    <div class="col-md-12"><strong>Created by:</strong> {{ $incident->creator->name }}</div>
                    <div class="col-md-12"><strong>Assigned to:</strong>
                        {{ $incident->assignee->name ?? 'Not assigned' }}</div>
                </div>
            </div>

            <div class="col-lg-8">
                @if ($incident->attachments->count())
                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3">Attachments</h5>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($incident->attachments as $attachment)
                                <div class="text-center">
                                    <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $attachment->path) }}"
                                            class="img-thumbnail border-0 shadow-sm" style="max-width: 100px;"
                                            alt="{{ $attachment->filename }}">
                                    </a>
                                    <div class="small text-muted mt-1">{{ $attachment->filename }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="mt-4">
                    <!-- Contenedor scrollable -->
                    @php
                        $commentLogs = $incident->logs->where('event_type', 'comment')->sortByDesc('created_at');
                    @endphp

                    <div class="border rounded mb-3" style="max-height: 250px; overflow-y: auto;">
                        @forelse ($commentLogs as $log)
                            <div class="card m-1 border-bottom pb-2 p-2">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $log->user->name ?? 'System' }}</strong>
                                    <span class="text-muted small">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mt-1">
                                    <p class="mb-0">{{ $log->description }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No comments yet.</p>
                        @endforelse
                    </div>

                    <!-- Formulario para añadir comentario -->
                    <form action="{{ route('incidents.comment', $incident) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="comment" class="form-label">Añadir Comentario</label>
                            <textarea name="description" id="comment" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">Adjuntar Archivo (opcional)</label>
                            <input type="file" name="attachment" id="file" class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">
                        </div>

                        <button type="submit" class="btn btn-primary">Enviar Comentario</button>
                    </form>

                </div>
            </div>

            <div class="col-lg-4">
                <div>
                    <h5 class="fw-semibold mb-3">Activity Log</h5>
                    <div style="max-height: 400px; overflow-y: auto;">
                        @php
                            $notCommentLogs = $incident->logs
                                ->where('event_type', '!=', 'comment')
                                ->sortByDesc('created_at');
                        @endphp

                        @forelse ($notCommentLogs as $log)
                            <div class="mb-3 p-2">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $log->user->name ?? 'System' }}</strong>
                                    <span class="text-muted small">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="text-muted small fst-italic">
                                    {{ ucfirst(str_replace('_', ' ', $log->event_type)) }}</div>
                                <div class="mt-1">
                                    <p class="mb-0 text-muted d-flex align-items-center flex-wrap gap-2">
                                        @if ($log->event_type === 'file_upload')
                                            <span class="badge bg-primary">File uploaded:
                                                {{ $log->new_value['filename'] }}</span>
                                        @else
                                            Changed from
                                            <span><strong>De:</strong>
                                                @if (is_array($log->old_value))
                                                    @foreach ($log->old_value as $value)
                                                        <span class="badge bg-secondary me-1">{{ $value ?? 'N/A' }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </span>

                                            <span><strong>A:</strong>
                                                @if (is_array($log->new_value))
                                                    @foreach ($log->new_value as $value)
                                                        <span class="badge bg-success me-1">{{ $value ?? 'N/A' }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </span>
                                        @endif
                                    </p>

                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No activity yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
