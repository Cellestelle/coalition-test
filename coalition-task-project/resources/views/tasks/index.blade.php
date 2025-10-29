@extends('layouts.app')

@section('content')
    <hgroup>
        <h1>Tasks</h1>
        <p>Drag to reorder. Priority updates automatically (1 = top).</p>
    </hgroup>

    <form method="GET" class="grid" style="grid-template-columns:1fr auto;">
        <select name="project_id" onchange="this.form.submit()">
            <option value="">All Projects</option>
            @foreach($projects as $p)
                <option value="{{ $p->id }}" @selected(($projectId ?? null) == $p->id)>{{ $p->name }}</option>
            @endforeach
        </select>
        <a href="{{ route('projects.index') }}" role="button" class="btn-sm">Manage Projects</a>
    </form>

    <div class="add-task-container">
        <details open>
            <summary class="add-task-summary">
                <span>Add Task</span>
                {{-- <button type="button" class="secondary btn-sm caret-btn" aria-label="toggle form">▼</button> --}}
            </summary>

            <form method="POST" action="{{ route('tasks.store') }}" class="grid"
                style="grid-template-columns:2fr 1fr auto;">
                @csrf
                <input name="name" placeholder="Task name..." required>
                <select name="project_id">
                    <option value="">No project</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" @selected(($projectId ?? null) == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
                <button type="submit">Add</button>
            </form>
        </details>
    </div>

    <table class="tasks-table">
        <thead>
            <tr>
                <th style="width:5rem">Priority</th>
                <th>Task</th>
                <th style="width:12rem">Project</th>
                <th style="width:7rem">Actions</th>
            </tr>
        </thead>
        <tbody id="taskList">
            @forelse($tasks as $t)
                <tr data-id="{{ $t->id }}">
                    <td>#{{ $t->priority }}</td>
                    <td>{{ $t->name }}</td>
                    <td>{{ $t->project?->name ?? '—' }}</td>
                    <td>
                        <form method="POST" action="{{ route('tasks.destroy', $t) }}" style="margin:0">
                            @csrf @method('DELETE')
                            <button class="secondary btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4"><em>No tasks yet.</em></td>
                </tr>
            @endforelse
        </tbody>
    </table>


    <script>
        const el = document.getElementById('taskList');
        if (el) {
            new Sortable(el, {
                animation: 150,
                ghostClass: 'sort-ghost',
                handle: 'tr',            // whole row is draggable
                onEnd: async () => {
                    const ordered = [...el.querySelectorAll('tr[data-id]')].map(r => r.dataset.id);
                    const res = await fetch("{{ route('tasks.reorder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({ ordered, projectId: "{{ $projectId ?? '' }}" })
                    });
                    if (res.ok) location.reload(); else alert('Reorder failed');
                }
            });
        }
    </script>
@endsection
