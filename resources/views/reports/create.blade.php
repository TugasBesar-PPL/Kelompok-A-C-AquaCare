<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajukan Laporan Air | AquaCare</title>
    <style>
        :root { color-scheme: light; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; color: #123b4a; background: #f2f8f8; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; }
        header { background: #075b68; color: white; padding: 18px max(24px, calc((100vw - 1040px) / 2)); }
        header a { color: inherit; text-decoration: none; font-size: 1.2rem; font-weight: 750; letter-spacing: .02em; }
        main { width: min(100% - 32px, 760px); margin: 42px auto; }
        .intro { margin-bottom: 24px; }
        .eyebrow { color: #087d83; font-size: .76rem; font-weight: 750; letter-spacing: .13em; text-transform: uppercase; }
        h1 { margin: 8px 0; font-size: clamp(1.8rem, 5vw, 2.5rem); }
        .muted { color: #54717a; line-height: 1.55; }
        form, .notice { background: white; border: 1px solid #d9e8e8; border-radius: 18px; box-shadow: 0 14px 38px #164b5510; }
        form { padding: clamp(20px, 5vw, 36px); }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .field { display: flex; flex-direction: column; gap: 7px; }
        .wide { grid-column: 1 / -1; }
        label { font-size: .9rem; font-weight: 700; }
        input, textarea { width: 100%; border: 1px solid #c6dada; border-radius: 10px; padding: 12px 13px; color: #123b4a; font: inherit; background: #fcfefe; }
        input:focus, textarea:focus { outline: 3px solid #14a0a044; border-color: #07828c; }
        textarea { resize: vertical; min-height: 116px; }
        .hint { color: #668087; font-size: .8rem; }
        .errors { padding: 14px 18px; color: #8d2626; background: #fff1f0; border: 1px solid #ffd0ca; border-radius: 12px; margin-bottom: 18px; }
        .success { padding: 14px 18px; color: #145b3c; background: #e8f8ef; border: 1px solid #bfe9ce; border-radius: 12px; margin-bottom: 18px; }
        button { border: 0; border-radius: 10px; padding: 13px 20px; color: white; background: #087d83; font: inherit; font-weight: 750; cursor: pointer; }
        button:hover { background: #05656d; }
        @media (max-width: 600px) { .grid { grid-template-columns: 1fr; } .wide { grid-column: auto; } main { margin-top: 28px; } }
    </style>
</head>
<body>
<header><a href="/">AquaCare</a></header>
<main>
    <section class="intro">
        <div class="eyebrow">Layanan warga</div>
        <h1>Ajukan laporan masalah air</h1>
        <p class="muted">Ceritakan kondisi air dan lokasi kejadian. Laporan akan tersimpan dengan status awal Menunggu.</p>
    </section>

    @if (session('success'))
        <div class="success" role="status">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="errors" role="alert">
            <strong>Periksa kembali data berikut:</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid">
            <div class="field">
                <label for="kategori">Kategori masalah</label>
                <input id="kategori" name="kategori" value="{{ old('kategori') }}" placeholder="Contoh: Air keruh" required maxlength="100">
            </div>
            <div class="field">
                <label for="wilayah">Wilayah</label>
                <input id="wilayah" name="wilayah" value="{{ old('wilayah') }}" placeholder="Kelurahan atau kecamatan" required maxlength="150">
            </div>
            <div class="field">
                <label for="latitude">Latitude</label>
                <input id="latitude" name="lokasi[latitude]" type="number" step="any" min="-90" max="90" value="{{ old('lokasi.latitude') }}" required>
            </div>
            <div class="field">
                <label for="longitude">Longitude</label>
                <input id="longitude" name="lokasi[longitude]" type="number" step="any" min="-180" max="180" value="{{ old('lokasi.longitude') }}" required>
            </div>
            <div class="field wide">
                <label for="alamat">Alamat kejadian</label>
                <input id="alamat" name="lokasi[alamat]" value="{{ old('lokasi.alamat') }}" required maxlength="1000">
            </div>
            <div class="field wide">
                <label for="deskripsi">Deskripsi masalah</label>
                <textarea id="deskripsi" name="deskripsi" required maxlength="10000" placeholder="Jelaskan kondisi air dan kapan masalah terjadi.">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="field wide">
                <label for="foto">Foto bukti</label>
                <input id="foto" name="foto" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required>
                <span class="hint">Format JPG atau PNG, ukuran maksimal 5 MB.</span>
            </div>
            <div class="field wide">
                <label for="water_point_id">ID titik air <span class="hint">(opsional)</span></label>
                <input id="water_point_id" name="water_point_id" type="number" min="1" value="{{ old('water_point_id') }}" placeholder="Isi jika tersedia">
            </div>
            <div class="wide"><button type="submit">Kirim laporan</button></div>
        </div>
    </form>
</main>
</body>
</html>
