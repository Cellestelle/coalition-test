@extends('layouts.app')
@section('content')
    <h1>Projects</h1>

    <form method="POST" action="{{ route('projects.store') }}" class="grid" style="grid-template-columns:2fr auto;">
        @csrf
        <input name="name" placeholder="New project name..." required>
        <button type="submit">Add</button>
    </form>

    <form method="POST" action="{{ route('projects.bulk') }}">
        @csrf
        <ul class="projects-list">
            @foreach($projects as $p)
                <li class="grid" style="grid-template-columns:2fr 2fr auto auto; gap:.5rem; align-items:center">
                    <strong>{{ $p->name }}</strong>

                    {{-- Rename input --}}
                    <input type="text" name="projects[{{ $p->id }}][name]" value="{{ $p->name }}" required>

                    {{-- Rename button --}}
                    <button type="submit" name="action" value="rename-{{ $p->id }}" class="secondary">Rename</button>

                    {{-- Delete button --}}
                    <button type="submit" name="action" value="delete-{{ $p->id }}" class="secondary">Delete</button>
                </li>
            @endforeach
        </ul>
    </form>

    <p><a href="{{ route('tasks.index') }}">&larr; Back to Tasks</a></p>
@endsection
