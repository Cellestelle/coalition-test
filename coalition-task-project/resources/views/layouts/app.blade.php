<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tasks</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
    <style>
        ul#taskList li {
            display: flex;
            gap: .75rem;
            align-items: center;
            justify-content: space-between
        }

        .btn-sm {
            /* padding: .35rem .6rem; */
            font-size: .9rem;
            /* line-height: 1; */
        }

        summary.no-caret {
            list-style: none;
        }

        summary.no-caret::-webkit-details-marker {
            display: none;
        }

        .no-caret-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: none;
            padding-right: .75rem;
        }

        .tasks-table {
            width: 100%;
            border-collapse: collapse;
        }

        .tasks-table th,
        .tasks-table td {
            border: 1px solid rgba(255, 255, 255, .12);
            padding: .6rem .75rem;
        }

        .tasks-table thead th {
            background: rgba(255, 255, 255, .06);
            text-align: left;
        }

        .tasks-table tbody tr {
            cursor: grab;
        }

        .tasks-table tbody tr:active {
            cursor: grabbing;
        }

        .tasks-table tbody tr:hover {
            background: rgba(255, 255, 255, .06);
        }

        .sort-ghost {
            opacity: .6;
        }

        .add-task-container {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-start;
        }

        .add-task-summary {
            display: flex;
            align-items: center;
            gap: .4rem;
            cursor: pointer;
            list-style: none;
        }

        .add-task-summary::-webkit-details-marker {
            display: none;
            /* hides the native caret */
        }

        .caret-btn {
            padding: 0 .5rem;
            line-height: 1;
            cursor: pointer;
        }

        .projects-table {
            width: 100%;
            border-collapse: collapse;
        }

        .projects-table th,
        .projects-table td {
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: .6rem .75rem;
            vertical-align: middle;
        }

        .projects-table thead th {
            background: rgba(255, 255, 255, 0.06);
            text-align: left;
        }

        .projects-table tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .inline-form {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin: 0;
        }

        .inline-form input {
            flex: 1;
        }

        .btn-sm {
            padding: .35rem .6rem;
            font-size: .9rem;
            line-height: 1;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
</head>

<body class="container">
    <main>
        @if(session('ok'))
        <article class="contrast">{{ session('ok') }}</article> @endif
        @yield('content')
    </main>
</body>

</html>
