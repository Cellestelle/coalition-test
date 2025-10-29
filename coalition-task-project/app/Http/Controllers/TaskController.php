<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    // List tasks by priority order
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


    // Create a new task and append it to the end
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

    // Edit tasks fields (name, project)
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'project_id' => ['sometimes', 'nullable', 'exists:projects,id'],
        ]);

        DB::transaction(function () use ($task, $data) {
            $oldProject = $task->project_id;

            $task->fill($data);

            // If project changed, push to bottom of new project’s list and densify the old one
            if ($task->isDirty('project_id')) {
                // densify old project priorities
                Task::where('project_id', $oldProject)
                    ->where('priority', '>', $task->priority)
                    ->decrement('priority');

                // assign new priority at bottom of new project
                $newMax = Task::where('project_id', $task->project_id)->max('priority') ?? 0;
                $task->priority = $newMax + 1;
            }

            $task->save();
        });

        return back()->with('ok', 'Task updated');
    }

    // Delete a task
    public function destroy(Task $task)
    {
        $pid = $task->project_id;
        $old = $task->priority;
        $task->delete();
        Task::where('project_id', $pid)->where('priority', '>', $old)->decrement('priority');
        return back()->with('ok', 'Task deleted');
    }


    // Rearrange tasks after drag-and-drop
    public function reorder(Request $request)
    {
        $data = $request->validate([
            'ordered' => ['required', 'array', 'min:1'],
            'projectId' => ['nullable', 'integer'], // optional if you want to scope client-side
        ]);

        $ids = $data['ordered'];

        DB::transaction(function () use ($ids) {
            foreach ($ids as $i => $id) {
                Task::whereKey($id)->update(['priority' => $i + 1]);
            }
        });

        return response()->json(['ok' => true]);
    }



}
