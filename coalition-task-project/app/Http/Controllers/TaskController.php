<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReorderTasksRequest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
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
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($task, $validated) {
            $oldProjectId = $task->project_id;
            $oldPriority = $task->priority;

            $task->fill($validated);

            if ($task->isDirty('project_id')) {
                // Densify priorities in the old project scope
                Task::where('project_id', $oldProjectId)
                    ->where('priority', '>', $oldPriority)
                    ->decrement('priority');

                // Push to bottom of the new project scope
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
    public function reorder(ReorderTasksRequest $request)
    {
        $validated = $request->validated();
        $ids = $validated['ordered'];

        DB::transaction(function () use ($ids) {
            foreach ($ids as $i => $id) {
                Task::whereKey($id)->update(['priority' => $i + 1]);
            }
        });

        return response()->json(['ok' => true]);
    }

}
