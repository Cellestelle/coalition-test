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
        <a href="{{ route('projects.index') }}" role="button" class="secondary">Manage Projects</a>
    </form>

    <details open>
        <summary>Add Task</summary>
        <form method="POST" action="{{ route('tasks.store') }}" class="grid" style="grid-template-columns:2fr 1fr auto;">
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
        @error('name')<small style="color:red">{{ $message }}</small>@enderror
    </details>

    <ul id="taskList">
        @forelse($tasks as $t)
            <li data-id="{{ $t->id }}">
                <div>
                    <strong>#{{ $t->priority }}</strong>
                    <span>{{ $t->name }}</span>
                    @if($t->project) <small>· {{ $t->project->name }}</small> @endif
                </div>
                <form method="POST" action="{{ route('tasks.destroy', $t) }}">
                    @csrf @method('DELETE')
                    <button class="secondary">Delete</button>
                </form>
            </li>
        @empty
            <li><em>No tasks yet.</em></li>
        @endforelse
    </ul>

    <script>
        const el = document.getElementById('taskList');
        if (el) {
            new Sortable(el, {
                animation: 150,
                onEnd: async () => {
                    const ordered = [...el.querySelectorAll('li')].map(li => li.dataset.id);
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
