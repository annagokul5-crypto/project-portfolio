<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        $shots = \App\Models\ProjectScreenshot::where('project_id', $project->id)->get();
        return view('projects.show', compact('project', 'shots'));
    }
}
