<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Semester;

abstract class Controller
{
    /**
     * Get authenticated user
     */
    protected function user(): ?User
    {
        return auth()->user();
    }

    /**
     * Get active Laratrust team_id from session
     */
    protected function teamId()
    {
        return session('active_team_id');
    }

    /**
     * Get active semester_id from session
     */
    protected function semesterId()
    {
        return session('active_semester_id') ?? Semester::where('periode_aktif', 1)->first()?->semester_id;
    }
}
