<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'urgency', 'impact', 'category','status', 'user_creator', 'user_asigned'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_creator');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'user_assigned');
    }

    public function logs()
    {
        return $this->hasMany(IncidentLog::class);
    }

    public function attachments()
    {
        return $this->hasMany(IncidentAttachment::class);
    }
}
