<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;

class ProjectInvitationsController extends Controller
{
    /**
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        request()->validate([
            'email' => ['required', 'exists:users,email'],
        ], [
            'email.exists' => 'The user you are inviting must have a Birdboard account.',
        ]);

        $user = User::whereEmail(request('email'))->first();

        $project->invite($user);

        return redirect($project->path());
    }
}
