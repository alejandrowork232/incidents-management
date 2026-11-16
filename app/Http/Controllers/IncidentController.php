<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentLog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\IncidentStatusUpdated;
use App\Models\IncidentAttachment;
use Illuminate\Support\Facades\Mail;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Incident::with(['creator', 'assignee']);

        if (auth()->user()->role === 'usuario'){
            $query->where('user_creator', auth()->id()); 
        }

        // ✅ FILTRO POR ROL: Soporte/Administrador NO ven cerradas, salvo que las hayan creado
        if (auth()->user()->role === 'soporte' || auth()->user()->role === 'administrador') {
            $query->where(function ($q) {
                $q->where('status', '!=', 'closed')
                    ->orWhere('user_creator', auth()->id());
            });
        }

        // ✅ FILTROS POR FORMULARIO
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        if ($request->filled('impact')) {
            $query->where('impact', $request->impact);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('created_by')) {
            $query->whereHas('creator', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->created_by . '%');
            });
        }

        if ($request->filled('assigned')) {
            $query->whereHas('assignee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->assigned . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $incidents = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $users = User::all();

        return view('incidents.incidents', compact('incidents', 'users'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('incidents.incident');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:hardware,software,network,security',
            'urgency' => 'required|in:low,medium,high',
            'impact' => 'required|in:low,medium,high',
        ]);
        Incident::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'urgency' => $request->urgency,
            'impact' => $request->impact,
            'user_creator' => auth()->id(),
        ]);

        return redirect()->route('incidents.index')->with('success', 'Incidencia creada correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function show(Incident $incident)
    {
        $this->authorize('view', $incident);

        $incident->load(['creator', 'assignee', 'attachments', 'logs.user']);
        $users = User::all();

        return view('incidents.show', compact('incident', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function edit(Incident $incident)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Incident $incident)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function destroy(Incident $incident)
    {
        //
    }


    public function assignToSelf(Request $request, Incident $incident)
    {
        $this->authorize('assignSelf', $incident); // usa la política

        $oldAssignedId = $incident->user_assigned;
        $newAssignedId = $request->assigned;

        // Obtener nombres
        $oldAssignedName = optional(User::find($oldAssignedId))->name ?? 'Sin asignar';
        $newAssignedName = optional(User::find($newAssignedId))->name ?? 'Sin asignar';

        // Actualizar incidencia
        $incident->user_assigned = $newAssignedId;
        $incident->updated_at = now();
        $incident->save();

        // Crear log
        IncidentLog::create([
            'incident_id' => $incident->id,
            'user_id' => auth()->id(),
            'event_type' => 'assignment',
            'old_value' => ['assigned' => $oldAssignedName],
            'new_value' => ['assigned' => $newAssignedName],
            'description' => 'Asignación actualizada manualmente',
        ]);

        return redirect()->back()->with('success', 'Te has asignado la incidencia.');
    }


    public function quickAssign(Request $request, Incident $incident)
    {
        $this->authorize('assignSelf', $incident);


        $oldAssignedId = $incident->user_assigned;
        $newAssignedId = $request->assigned_user;

        // Obtener nombres
        $oldAssignedName = optional(User::find($oldAssignedId))->name ?? 'Sin asignar';
        $newAssignedName = optional(User::find($newAssignedId))->name ?? 'Sin asignar';

        $incident->user_assigned = $newAssignedId;
        $incident->updated_at = now();
        $incident->save();

        IncidentLog::create([
            'incident_id' => $incident->id,
            'user_id' => auth()->id(),
            'event_type' => 'assignment',
            'old_value' => ['assigned' => $oldAssignedName],
            'new_value' => ['assigned' => $newAssignedName],
            'description' => 'Asignación actualizada por selección rápida',
        ]);

        return redirect()->back()->with('success', 'Te has asignado la incidencia.');
    }



    public function updateStatus(Request $request, Incident $incident)
    {
        $request->validate([
            'status' => 'required|in:reopen,open,pending,in_progress,closed'
        ]);

        $oldStatus = $incident->status;
        $incident->status = $request->status;
        $incident->updated_at = now();
        $incident->save();

        IncidentLog::create([
            'incident_id' => $incident->id,
            'user_id' => auth()->id(),
            'event_type' => 'status_change',
            'old_value' => ['status' => $oldStatus],
            'new_value' => ['status' => $incident->status],
            'description' => 'Status updated manually',
        ]);

        Mail::to($incident->creator->email)->send(new IncidentStatusUpdated($incident, $oldStatus, $incident->status));

        return redirect()->back()->with('success', 'Te has asignado la incidencia.');
    }

    public function addComment(Request $request, Incident $incident)
    {
        $request->validate([
            'description' => 'required|string|max:1000',
            'attachment' => 'nullable|file|max:10240', // Máx 10MB
        ]);

        // 1. Guardar comentario
        IncidentLog::create([
            'incident_id' => $incident->id,
            'user_id' => auth()->id(),
            'event_type' => 'comment',
            'description' => $request->description,
        ]);

        // 2. Si hay archivo adjunto, guardarlo
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('incidents/attachments', 'public');

            $attachment = IncidentAttachment::create([
                'incident_id' => $incident->id,
                'user_id' => auth()->id(),
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
            ]);

            // 3. Crear log del archivo subido
            IncidentLog::create([
                'incident_id' => $incident->id,
                'user_id' => auth()->id(),
                'event_type' => 'file_upload',
                'description' => 'Archivo subido: ' . $attachment->filename,
                'new_value' => ['filename' => $attachment->filename, 'path' => $path],
            ]);
        }

        return back()->with('success', 'Comentario añadido correctamente.');
    }
}
