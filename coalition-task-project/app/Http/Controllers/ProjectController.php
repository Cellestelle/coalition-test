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

    public function bulk(Request $request)
    {
        $action = $request->input('action'); // e.g., "rename-3" or "delete-7"

        if (str_starts_with($action, 'rename-')) {
            $id = (int) str_replace('rename-', '', $action);
            $name = $request->input("projects.$id.name");
            Project::findOrFail($id)->update(['name' => $name]);
            return back()->with('ok', 'Project renamed');
        }

        if (str_starts_with($action, 'delete-')) {
            $id = (int) str_replace('delete-', '', $action);
            Project::findOrFail($id)->delete();
            return back()->with('ok', 'Project deleted');
        }

        return back();
    }

}
