<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Books') · {{ config('app.name') }}</title>
    <style>
        :root { --primary: #2563eb; --primary-dark: #1d4ed8; --danger: #dc2626; --border: #e5e7eb; --muted: #6b7280; --bg: #f3f4f6; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--bg); color: #111827; }
        header { background: #111827; color: #fff; padding: 16px 0; }
        header a { color: #fff; text-decoration: none; font-weight: 600; font-size: 1.15rem; }
        .container { max-width: 960px; margin: 0 auto; padding: 0 16px; }
        main { padding: 24px 0 48px; }
        .card { background: #fff; border: 1px solid var(--border); border-radius: 8px; padding: 24px; }
        h1 { font-size: 1.4rem; margin: 0 0 16px; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 12px; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .search { display: flex; gap: 8px; flex: 1; min-width: 240px; }
        input[type=text], input[type=number] { width: 100%; padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.95rem; }
        input.invalid { border-color: var(--danger); }
        .btn { display: inline-block; padding: 9px 16px; border: 0; border-radius: 6px; font-size: 0.9rem; font-weight: 500; cursor: pointer; text-decoration: none; background: var(--primary); color: #fff; white-space: nowrap; }
        .btn:hover { background: var(--primary-dark); }
        .btn-secondary { background: #e5e7eb; color: #111827; }
        .btn-secondary:hover { background: #d1d5db; }
        .btn-danger { background: var(--danger); }
        .btn-danger:hover { background: #b91c1c; }
        .btn-sm { padding: 6px 12px; font-size: 0.85rem; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid var(--border); }
        th { background: #f9fafb; font-size: 0.8rem; text-transform: uppercase; letter-spacing: .03em; color: var(--muted); }
        td.actions { display: flex; gap: 6px; }
        .empty { text-align: center; color: var(--muted); padding: 32px; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert ul { margin: 6px 0 0; padding-left: 20px; }
        .field { margin-bottom: 16px; }
        label { display: block; font-weight: 500; margin-bottom: 6px; }
        .error { color: var(--danger); font-size: 0.85rem; margin-top: 4px; }
        .form-actions { display: flex; gap: 8px; margin-top: 24px; }
        .pagination { margin-top: 16px; }
        .pagination ul { display: flex; justify-content: center; gap: 8px; list-style: none; padding: 0; margin: 0; }
        .pagination a, .pagination span { display: inline-block; padding: 6px 12px; border: 1px solid var(--border); border-radius: 6px; text-decoration: none; color: #111827; }
        .pagination .disabled span { color: var(--muted); }
    </style>
</head>
<body>
    <header>
        <div class="container"><a href="{{ route('books.index') }}">📚 {{ config('app.name') }}</a></div>
    </header>

    <main class="container">
        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
