@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <span>🗺️</span> Peta Sebaran Titik Air (PBI-10)
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Visualisasi titik sumber air dengan indikator warna status kelayakan berdasarkan baku mutu terkini.
            </p>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('water-points.map') }}" class="flex flex-wrap items-center gap-2.5">
            <div>
                <select name="status" class="text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status Kelayakan</option>
                    <option value="Layak" {{ ($filters['status'] ?? '') == 'Layak' ? 'selected' : '' }}>🟢 Layak</option>
                    <option value="Waspada" {{ ($filters['status'] ?? '') == 'Waspada' ? 'selected' : '' }}>🟡 Waspada</option>
                    <option value="Tidak Layak" {{ ($filters['status'] ?? '') == 'Tidak Layak' ? 'selected' : '' }}>🔴 Tidak Layak</option>
                </select>
            </div>
            <div>
                <select name="wilayah" class="text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Wilayah</option>
                    @foreach($daftarWilayah as $wil)
                        <option value="{{ $wil }}" {{ ($filters['wilayah'] ?? '') == $wil ? 'selected' : '' }}>{{ $wil }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow transition">
                Terapkan Filter
            </button>
            <a href="{{ route('water-points.map') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg transition">
                Reset
            </a>
        </form>
    </div>

    <!-- Map & Legend Container -->
    <div class="relative bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Legend Bar -->
        <div class="bg-slate-100/80 backdrop-blur px-4 py-2 border-b border-slate-200 flex flex-wrap items-center justify-between text-xs text-slate-600 gap-3">
            <div class="flex items-center gap-4">
                <span class="font-semibold text-slate-700">Legenda Status:</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block shadow-sm"></span> Layak</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-500 inline-block shadow-sm"></span> Waspada</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-rose-500 inline-block shadow-sm"></span> Tidak Layak</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-slate-400 inline-block shadow-sm"></span> Belum Diuji</span>
            </div>
            <div>
                Total Titik Ditemukan: <span class="font-bold text-slate-900">{{ count($points) }}</span>
            </div>
        </div>

        <!-- Leaflet Map Div -->
        <div id="map" class="w-full h-[580px] z-10"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data titik dari backend
        const waterPoints = @json($points);

        // Inisialisasi peta berpusat pada koordinat default titik atau wilayah Bandung
        const defaultLat = waterPoints.length > 0 ? waterPoints[0].latitude : -6.9745210;
        const defaultLng = waterPoints.length > 0 ? waterPoints[0].longitude : 107.6301240;

        const map = L.map('map').setView([defaultLat, defaultLng], 14);

        // Tile layer OpenStreetMap (Sesuai TEK-04 & Tabel 8)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Warna Marker berdasarkan Status Kelayakan
        function getColorByStatus(status) {
            switch ((status || '').toLowerCase()) {
                case 'layak': return '#10B981'; // Hijau
                case 'waspada': return '#F59E0B'; // Kuning
                case 'tidak layak': return '#EF4444'; // Merah
                default: return '#94A3B8'; // Abu-abu
            }
        }

        const markers = [];

        // Pasang marker untuk tiap titik sumber air
        waterPoints.forEach(point => {
            const color = getColorByStatus(point.status_terkini);

            // Custom Marker Circle Pin
            const marker = L.circleMarker([point.latitude, point.longitude], {
                radius: 10,
                fillColor: color,
                color: '#FFFFFF',
                weight: 3,
                opacity: 1,
                fillOpacity: 0.95
            }).addTo(map);

            // Konten Popup Detail Singkat (Sesuai UC-15 Alur 6)
            const popupContent = `
                <div class="p-2 text-slate-800 text-xs leading-relaxed max-w-[240px]">
                    <div class="font-bold text-sm text-slate-900 mb-1">${point.nama}</div>
                    <div class="mb-1 text-slate-500 font-medium">Jenis: <span class="text-slate-700">${point.jenis}</span></div>
                    <div class="mb-2 text-slate-500">Alamat: <span class="text-slate-700">${point.alamat} (${point.wilayah})</span></div>
                    
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-500">Status:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold text-white" style="background-color: ${color}">
                            ${point.status_terkini || 'Belum Diuji'}
                        </span>
                    </div>
                    <div class="mt-1 text-[10px] text-slate-400">
                        Uji Terakhir: ${point.pengukuran_terakhir ? point.pengukuran_terakhir : 'Belum pernah'}
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent);
            markers.push(marker);
        });

        // Zoom fit bounds jika terdapat titik
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.15));
        }
    });
</script>
@endpush

