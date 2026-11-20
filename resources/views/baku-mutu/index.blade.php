@extends('layouts.baku-mutu')

@section('title', 'Daftar Baku Mutu')

@section('content')
    <h1>Baku Mutu Parameter Air</h1>
    <p class="muted">Batas yang berlaku saat ini untuk setiap parameter kualitas air.</p>

    @if (session('status'))
        <div class="notice">{{ session('status') }}</div>
    @endif

    <section class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Parameter</th>
                        <th>Satuan</th>
                        <th>Nilai minimum</th>
                        <th>Nilai maksimum</th>
                        <th>Waspada minimum</th>
                        <th>Waspada maksimum</th>
                        <th>Berlaku sejak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parameters as $parameter)
                        <tr>
                            <td>{{ $parameter->nama_parameter }}</td>
                            <td>{{ $parameter->satuan }}</td>
                            <td>{{ number_format((float) $parameter->nilai_minimum, 2, ',', '.') }}</td>
                            <td>{{ number_format((float) $parameter->nilai_maksimum, 2, ',', '.') }}</td>
                            <td>{{ number_format((float) $parameter->batas_waspada_min, 2, ',', '.') }}</td>
                            <td>{{ number_format((float) $parameter->batas_waspada_maks, 2, ',', '.') }}</td>
                            <td>{{ $parameter->tanggal_berlaku->format('d-m-Y') }}</td>
                            <td class="actions">
                                <a class="action" href="{{ route('baku-mutu.admin.edit', $parameter->kode_parameter) }}">Ubah</a>
                                <a class="action secondary" href="{{ route('baku-mutu.admin.history', $parameter->kode_parameter) }}">Riwayat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Belum ada data baku mutu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
