<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Laporan #{{ $report->laporan_id }} | AquaCare</title>
    <style>
        :root { color-scheme: light; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; color: #123b4a; background: #f2f8f8; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; }
        header { background: #075b68; color: white; padding: 18px max(24px, calc((100vw - 1040px) / 2)); }
        header a { color: inherit; text-decoration: none; font-size: 1.2rem; font-weight: 750; letter-spacing: .02em; }
        main { width: min(100% - 32px, 900px); margin: 42px auto; }
        .back { display: inline-block; margin-bottom: 20px; color: #087d83; font-weight: 700; text-decoration: none; }
        .eyebrow { color: #087d83; font-size: .76rem; font-weight: 750; letter-spacing: .13em; text-transform: uppercase; }
        h1 { margin: 8px 0 24px; font-size: clamp(1.8rem, 5vw, 2.5rem); }
        h2 { margin: 0 0 18px; font-size: 1.2rem; }
        .muted { color: #54717a; line-height: 1.55; }
        .panel { margin-bottom: 22px; padding: clamp(20px, 4vw, 32px); background: white; border: 1px solid #d9e8e8; border-radius: 18px; box-shadow: 0 14px 38px #164b5510; }
        .details { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
        .detail { min-width: 0; }
        .detail.wide { grid-column: 1 / -1; }
        dt { margin-bottom: 6px; color: #54717a; font-size: .78rem; font-weight: 750; letter-spacing: .05em; text-transform: uppercase; }
        dd { margin: 0; line-height: 1.55; overflow-wrap: anywhere; }
        .status { display: inline-flex; border-radius: 999px; padding: 6px 11px; color: #075b68; background: #e5f5f3; font-size: .86rem; font-weight: 700; }
        .photo { display: block; width: min(100%, 560px); max-height: 420px; margin-top: 8px; border: 1px solid #d9e8e8; border-radius: 12px; background: #f7fbfb; object-fit: contain; }
        .history-list { display: grid; gap: 14px; }
        .history-item { padding: 18px; border: 1px solid #d9e8e8; border-radius: 12px; }
        .history-item h3 { margin: 0 0 8px; font-size: 1rem; }
        .history-time { margin: 0 0 12px; color: #54717a; font-size: .88rem; }
        .empty { padding: 24px; border: 1px dashed #b9d3d4; border-radius: 12px; text-align: center; }
        .empty p { margin: 0; }
        @media (max-width: 600px) { main { margin-top: 28px; } .details { grid-template-columns: 1fr; } .detail.wide { grid-column: auto; } }
    </style>
</head>
<body>
<header><a href="/">AquaCare</a></header>
<main>
    <a class="back" href="{{ route('reports.index') }}">&larr; Kembali ke laporan saya</a>
    <div class="eyebrow">Pelacakan laporan</div>
    <h1>Detail laporan #{{ $report->laporan_id }}</h1>

    <section class="panel" aria-labelledby="report-details-heading">
        <h2 id="report-details-heading">Informasi laporan</h2>
        <dl class="details">
            <div class="detail">
                <dt>ID laporan</dt>
                <dd>#{{ $report->laporan_id }}</dd>
            </div>
            <div class="detail">
                <dt>Status laporan</dt>
                <dd><span class="status">{{ $report->status_laporan }}</span></dd>
            </div>
            <div class="detail">
                <dt>Kategori masalah</dt>
                <dd>{{ $report->kategori_masalah }}</dd>
            </div>
            <div class="detail">
                <dt>Wilayah</dt>
                <dd>{{ $report->wilayah }}</dd>
            </div>
            <div class="detail">
                <dt>Latitude</dt>
                <dd>{{ $report->lokasi['latitude'] ?? '—' }}</dd>
            </div>
            <div class="detail">
                <dt>Longitude</dt>
                <dd>{{ $report->lokasi['longitude'] ?? '—' }}</dd>
            </div>
            <div class="detail wide">
                <dt>Alamat</dt>
                <dd>{{ $report->lokasi['alamat'] ?? '—' }}</dd>
            </div>
            <div class="detail wide">
                <dt>Deskripsi</dt>
                <dd>{{ $report->deskripsi }}</dd>
            </div>
            <div class="detail">
                <dt>Tanggal laporan</dt>
                <dd>{{ $report->tanggal_laporan->format('d/m/Y H:i') }}</dd>
            </div>
            <div class="detail wide">
                <dt>Foto laporan</dt>
                <dd><img class="photo" src="{{ route('reports.photo', $report->laporan_id) }}" alt="Foto laporan #{{ $report->laporan_id }}" loading="lazy"></dd>
            </div>
        </dl>
    </section>

    <section class="panel" aria-labelledby="history-heading">
        <h2 id="history-heading">Riwayat penanganan</h2>
        @if (! $historyAvailable)
            <div class="empty"><p class="muted">Riwayat penanganan belum tersedia.</p></div>
        @elseif ($history->isEmpty())
            <div class="empty"><p class="muted">Laporan ini belum memiliki riwayat penanganan.</p></div>
        @else
            <div class="history-list">
                @foreach ($history as $item)
                    <article class="history-item">
                        <h3>{{ $item->status_penanganan }}</h3>
                        <p class="history-time">{{ \Illuminate\Support\Carbon::parse($item->tanggal_pembaruan)->format('d/m/Y H:i') }}</p>
                        @if ($item->catatan)
                            <p>{{ $item->catatan }}</p>
                        @else
                            <p class="muted">Tidak ada catatan.</p>
                        @endif
                        @if ($item->foto_bukti)
                            <img class="photo" src="{{ route('reports.history-photo', ['id' => $report->laporan_id, 'penangananId' => $item->penanganan_id]) }}" alt="Foto bukti riwayat {{ $item->status_penanganan }}" loading="lazy">
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</main>
</body>
</html>
