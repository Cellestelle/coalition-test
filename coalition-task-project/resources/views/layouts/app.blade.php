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
