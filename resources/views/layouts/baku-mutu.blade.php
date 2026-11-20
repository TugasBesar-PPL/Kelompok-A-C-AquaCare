<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Baku Mutu Air') - {{ config('app.name', 'AquaCare') }}</title>
    <style>
        :root { color-scheme: light; font-family: Arial, sans-serif; color: #172033; background: #f3f6fa; }
        * { box-sizing: border-box; }
        body { margin: 0; }
        header { background: #075985; color: #fff; padding: 18px max(24px, calc((100% - 1120px) / 2)); }
        header a { color: #fff; text-decoration: none; font-weight: 700; }
        main { max-width: 1120px; margin: 32px auto; padding: 0 20px; }
        h1 { margin: 0 0 8px; font-size: 28px; }
        h2 { font-size: 19px; }
        .muted { color: #64748b; }
        .card { background: #fff; border: 1px solid #dbe3ec; border-radius: 12px; padding: 22px; box-shadow: 0 4px 14px #0f172a0a; }
        .notice { margin: 16px 0; padding: 12px 16px; border-radius: 8px; background: #dcfce7; color: #166534; }
        .error { color: #b91c1c; font-size: 14px; margin-top: 5px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 760px; }
        th, td { padding: 12px 10px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { color: #475569; font-size: 13px; white-space: nowrap; }
        td { font-size: 14px; }
        .actions { white-space: nowrap; }
        a.action, button { display: inline-block; border: 0; border-radius: 7px; padding: 9px 12px; background: #0369a1; color: #fff; text-decoration: none; cursor: pointer; font-size: 14px; }
        a.secondary { background: #e0f2fe; color: #075985; }
        form { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        label { display: block; font-size: 14px; font-weight: 600; }
        input { display: block; width: 100%; margin-top: 7px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 7px; font: inherit; }
        .form-actions { grid-column: 1 / -1; display: flex; gap: 10px; align-items: center; }
        .form-actions a { color: #0369a1; }
        @media (max-width: 640px) { main { margin-top: 20px; } form { grid-template-columns: 1fr; } .form-actions { grid-column: 1; } }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('baku-mutu.admin.index') }}">AquaCare · Pengaturan Baku Mutu</a>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>
