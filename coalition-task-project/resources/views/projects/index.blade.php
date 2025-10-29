@extends('layouts.app')
@section('content')
    <h1>Projects</h1>

    <form method="POST" action="{{ route('projects.store') }}" class="grid" style="grid-template-columns:2fr auto;">
        @csrf
        <input name="name" placeholder="New project name..." required>
        <button type="submit">Add</button>
    </form>

    <ul>
        @foreach($projects as $p)
            <li class="grid" style="grid-template-columns:2fr auto auto; gap:.5rem; align-items:center">
                <strong>{{ $p->name }}</strong>
                <form method="POST" action="{{ route('projects.update', $p) }}">
                    @csrf @method('PUT')
                    <input name="name" value="{{ $p->name }}" required>
                    <button class="secondary">Rename</button>
                </form>
                <form method="POST" action="{{ route('projects.destroy', $p) }}">
                    @csrf @method('DELETE')
                    <button class="secondary">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>

    <p><a href="{{ route('tasks.index') }}">&larr; Back to Tasks</a></p>
@endsection
