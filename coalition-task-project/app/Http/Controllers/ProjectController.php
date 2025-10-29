<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{

    // Index page and list of projects
    public function index()
    {
        $projects = Project::orderBy('name')->get();
        return view('projects.index', compact('projects'));
    }

    // Store project
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Project::create($data);

        return back()->with('ok', 'Project created');
    }


    // Update project details
    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $project->update($data);

        return back()->with('ok', 'Project updated');
    }


    // Delete a project
    public function destroy(Project $project)
    {
        $project->delete();

        return back()->with('ok', 'Project deleted');
    }
}
