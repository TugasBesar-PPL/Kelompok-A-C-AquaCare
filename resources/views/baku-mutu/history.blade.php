@extends('layouts.baku-mutu')

@section('title', 'Riwayat Baku Mutu')

@section('content')
    <h1>Riwayat Baku Mutu: {{ $history->first()->nama_parameter }}</h1>
    <p class="muted">Versi terbaru ditampilkan lebih dahulu.</p>

    <section class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal berlaku</th>
                        <th>Nilai minimum</th>
                        <th>Nilai maksimum</th>
                        <th>Waspada minimum</th>
                        <th>Waspada maksimum</th>
                        <th>Diubah oleh (ID)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $version)
                        <tr>
                            <td>{{ $version->tanggal_berlaku->format('d-m-Y') }}</td>
                            <td>{{ number_format((float) $version->nilai_minimum, 2, ',', '.') }} {{ $version->satuan }}</td>
                            <td>{{ number_format((float) $version->nilai_maksimum, 2, ',', '.') }} {{ $version->satuan }}</td>
                            <td>{{ number_format((float) $version->batas_waspada_min, 2, ',', '.') }} {{ $version->satuan }}</td>
                            <td>{{ number_format((float) $version->batas_waspada_maks, 2, ',', '.') }} {{ $version->satuan }}</td>
                            <td>{{ $version->diubah_oleh ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p><a href="{{ route('baku-mutu.admin.index') }}">Kembali ke daftar baku mutu</a></p>
    </section>
@endsection
