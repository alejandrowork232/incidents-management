<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncidentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }


    public function assignSelf(User $user, Incident $incident)
    {
        // Si no hay nadie asignado, soporte o admin pueden asignarse
        if (is_null($incident->user_assigned)) {
            return in_array($user->role, ['soporte', 'administrador']);
        }

        // Si ya hay alguien asignado...
        $currentAssignee = $incident->assignee;

        // Si el que está asignado es admin y el que intenta reasignar no lo es → denegar
        if ($currentAssignee && $currentAssignee->role === 'administrador' && $user->role !== 'administrador') {
            return false;
        }

        // En cualquier otro caso, permitir si el usuario es soporte o administrador
        return in_array($user->role, ['soporte', 'administrador']);
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Incident $incident)
    {
        return in_array($user->role, ['soporte', 'administrador']) ||
           $incident->user_creator === auth()->id();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Incident $incident)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Incident $incident)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Incident $incident)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Incident $incident)
    {
        //
    }
}
