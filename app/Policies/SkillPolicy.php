<?php

namespace App\Policies;

use App\Models\Seeker;
use App\Models\Skill;
use Illuminate\Auth\Access\Response;

class SkillPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Seeker $seeker): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Seeker $seeker, Skill $skill): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Seeker $seeker): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Seeker $seeker, Skill $skill): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Seeker $seeker, Skill $skill): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Seeker $seeker, Skill $skill): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Seeker $seeker, Skill $skill): bool
    {
        //
    }
}
