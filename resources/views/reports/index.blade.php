<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Saya | AquaCare</title>
    <style>
        :root { color-scheme: light; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; color: #123b4a; background: #f2f8f8; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; }
        header { background: #075b68; color: white; padding: 18px max(24px, calc((100vw - 1040px) / 2)); }
        header a { color: inherit; text-decoration: none; font-size: 1.2rem; font-weight: 750; letter-spacing: .02em; }
        main { width: min(100% - 32px, 1040px); margin: 42px auto; }
        .intro { margin-bottom: 24px; }
        .eyebrow { color: #087d83; font-size: .76rem; font-weight: 750; letter-spacing: .13em; text-transform: uppercase; }
        h1 { margin: 8px 0; font-size: clamp(1.8rem, 5vw, 2.5rem); }
        .muted { color: #54717a; line-height: 1.55; }
        .panel { background: white; border: 1px solid #d9e8e8; border-radius: 18px; box-shadow: 0 14px 38px #164b5510; padding: clamp(18px, 4vw, 30px); }
        .filter { display: flex; flex-wrap: wrap; align-items: end; gap: 12px; margin-bottom: 22px; }
        .field { display: flex; flex-direction: column; gap: 7px; min-width: min(100%, 260px); }
        label { font-size: .9rem; font-weight: 700; }
        select { min-height: 44px; border: 1px solid #c6dada; border-radius: 10px; padding: 10px 13px; color: #123b4a; font: inherit; background: #fcfefe; }
        select:focus { outline: 3px solid #14a0a044; border-color: #07828c; }
        button, .button-link { display: inline-flex; align-items: center; justify-content: center; min-height: 44px; border: 0; border-radius: 10px; padding: 10px 18px; color: white; background: #087d83; font: inherit; font-weight: 750; text-decoration: none; cursor: pointer; }
        button:hover, .button-link:hover { background: #05656d; }
        .table-wrap { overflow-x: auto; border: 1px solid #d9e8e8; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 14px 16px; border-bottom: 1px solid #e5eeee; vertical-align: middle; }
        th { color: #54717a; background: #f7fbfb; font-size: .78rem; letter-spacing: .05em; text-transform: uppercase; white-space: nowrap; }
        tbody tr:last-child td { border-bottom: 0; }
        .report-id { color: #087d83; font-weight: 750; }
        .status { display: inline-flex; border-radius: 999px; padding: 5px 10px; color: #075b68; background: #e5f5f3; font-size: .82rem; font-weight: 700; white-space: nowrap; }
        .empty { padding: 38px 18px; border: 1px dashed #b9d3d4; border-radius: 12px; text-align: center; }
        .empty h2 { margin: 0 0 8px; font-size: 1.15rem; }
        .empty p { margin: 0 auto 18px; max-width: 480px; }
        .pagination { margin-top: 20px; }
        .pagination nav { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .pagination nav > div:first-child { color: #54717a; font-size: .9rem; }
        .pagination nav > div:last-child > span, .pagination nav > div:last-child > a { display: inline-flex; gap: 4px; }
        .pagination nav a, .pagination nav span[aria-current] span, .pagination nav span[aria-disabled] span { display: inline-block; padding: 8px 11px; border: 1px solid #d9e8e8; border-radius: 8px; color: #075b68; background: white; text-decoration: none; }
        .pagination nav span[aria-current] span { color: white; border-color: #087d83; background: #087d83; }
        .pagination nav span[aria-disabled] span { color: #91a6a8; }
        @media (max-width: 600px) { main { margin-top: 28px; } .filter { align-items: stretch; } .field { width: 100%; } .filter button { width: 100%; } th, td { padding: 12px; } .pagination nav { align-items: flex-start; flex-direction: column; } }
    </style>
</head>
<body>
<header><a href="/">AquaCare</a></header>
<main>
    <section class="intro">
        <div class="eyebrow">Layanan warga</div>
        <h1>Laporan saya</h1>
        <p class="muted">Lihat laporan masalah air yang pernah Anda ajukan.</p>
    </section>

    <section class="panel" aria-label="Daftar laporan saya">
        <form class="filter" method="GET" action="{{ route('reports.index') }}">
            <div class="field">
                <label for="status">Filter status laporan</label>
                <select id="status" name="status">
                    <option value="">Semua status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Terapkan filter</button>
        </form>

        @if ($reports->total() === 0)
            <div class="empty">
                @if ($hasReports)
                    <h2>Tidak ada laporan dengan status ini</h2>
                    <p class="muted">Coba pilih status lain atau tampilkan semua laporan Anda.</p>
                    <a class="button-link" href="{{ route('reports.index') }}">Tampilkan semua laporan</a>
                @else
                    <h2>Belum ada laporan</h2>
                    <p class="muted">Laporan yang Anda kirim akan muncul di halaman ini.</p>
                    <a class="button-link" href="{{ route('reports.create') }}">Ajukan laporan</a>
                @endif
            </div>
        @elseif ($reports->isEmpty())
            <div class="empty">
                <h2>Halaman ini tidak berisi laporan</h2>
                <p class="muted">Tidak ada laporan pada halaman yang diminta. Kembali ke halaman pertama untuk melihat hasil.</p>
                <a class="button-link" href="{{ route('reports.index', ['status' => $selectedStatus, 'page' => 1]) }}">Kembali ke halaman pertama</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th scope="col">ID laporan</th>
                        <th scope="col">Kategori masalah</th>
                        <th scope="col">Wilayah</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tanggal laporan</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td class="report-id"><a href="{{ route('reports.show', $report->laporan_id) }}">#{{ $report->laporan_id }}</a></td>
                            <td>{{ $report->kategori_masalah }}</td>
                            <td>{{ $report->wilayah }}</td>
                            <td><span class="status">{{ $report->status_laporan }}</span></td>
                            <td>{{ $report->tanggal_laporan->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if ($reports->hasPages())
                <div class="pagination">{{ $reports->links() }}</div>
            @endif
        @endif
    </section>
</main>
</body>
</html>
