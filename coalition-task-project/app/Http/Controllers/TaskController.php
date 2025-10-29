<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Models\Project;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        $projects = Project::orderBy('name')->get();
        $tasks = Task::when(
            $projectId,
            fn($q) => $q->where('project_id', $projectId)
        )
            ->orderBy('priority')->get();
        return view('tasks.index', compact('projects', 'tasks', 'projectId'));
    }


    public function store(StoreTaskRequest $req)
    {
        $projectId = $req->validated()['project_id'] ?? null;
        $max = Task::where('project_id', $projectId)->max('priority') ?? 0;
        Task::create([
            'name' => $req->validated()['name'],
            'project_id' => $projectId,
            'priority' => $max + 1,
        ]);
        return back();
    }

    public function destroy(Task $task)
    {
        $pid = $task->project_id;
        $old = $task->priority;
        $task->delete();
        Task::where('project_id', $pid)->where('priority', '>', $old)->decrement('priority');
        return back();
    }

}
