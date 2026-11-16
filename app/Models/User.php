<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Incidencias creadas por este usuario
    public function incidents()
    {
        return $this->hasMany(Incident::class, 'user_creator');
    }

    // Incidencias asignadas a este usuario
    public function assignedIncidents()
    {
        return $this->hasMany(Incident::class, 'user_assigned');
    }

    // Logs de actividad realizados por este usuario
    public function incidentLogs()
    {
        return $this->hasMany(IncidentLog::class);
    }

    // Archivos subidos por este usuario
    public function incidentAttachments()
    {
        return $this->hasMany(IncidentAttachment::class);
    }
}
