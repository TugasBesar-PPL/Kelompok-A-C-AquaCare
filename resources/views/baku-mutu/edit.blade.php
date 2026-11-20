@extends('layouts.baku-mutu')

@section('title', 'Ubah Baku Mutu')

@section('content')
    <h1>Ubah Baku Mutu: {{ $parameter->nama_parameter }}</h1>
    <p class="muted">Simpan perubahan sebagai versi baru. Versi sebelumnya tetap tersedia di riwayat.</p>

    <section class="card">
        <form method="POST" action="{{ route('baku-mutu.admin.update', $parameter->kode_parameter) }}">
            @csrf
            @method('PUT')

            <label>
                Nilai minimum ({{ $parameter->satuan }})
                <input type="number" step="0.01" name="nilai_minimum" value="{{ old('nilai_minimum', $parameter->nilai_minimum) }}" required>
                @error('nilai_minimum') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label>
                Nilai maksimum ({{ $parameter->satuan }})
                <input type="number" step="0.01" name="nilai_maksimum" value="{{ old('nilai_maksimum', $parameter->nilai_maksimum) }}" required>
                @error('nilai_maksimum') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label>
                Batas waspada minimum
                <input type="number" step="0.01" name="batas_waspada_min" value="{{ old('batas_waspada_min', $parameter->batas_waspada_min) }}" required>
                @error('batas_waspada_min') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label>
                Batas waspada maksimum
                <input type="number" step="0.01" name="batas_waspada_maks" value="{{ old('batas_waspada_maks', $parameter->batas_waspada_maks) }}" required>
                @error('batas_waspada_maks') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label>
                Tanggal berlaku
                <input type="date" name="tanggal_berlaku" min="{{ now()->toDateString() }}" value="{{ old('tanggal_berlaku', $parameter->tanggal_berlaku->toDateString()) }}" required>
                @error('tanggal_berlaku') <span class="error">{{ $message }}</span> @enderror
            </label>

            <div class="form-actions">
                <button type="submit">Simpan versi baru</button>
                <a href="{{ route('baku-mutu.admin.index') }}">Kembali</a>
            </div>
        </form>
    </section>
@endsection
