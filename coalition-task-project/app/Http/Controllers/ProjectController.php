<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{

    // Index page and list of projects
    public function index()
    {
        $projects = Project::orderBy('name')->get();
        return view('projects.index', compact('projects'));
    }

    // Store project
public function store(StoreProjectRequest $request)
    {
        Project::create($request->validated());
        return back()->with('ok', 'Project created');
    }

    // Update project details
     public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return back()->with('ok', 'Project updated');
    }


    // Delete a project
    public function destroy(Project $project)
    {
        $project->delete();

        return back()->with('ok', 'Project deleted');
    }
}
