<div>
<div class="page"><h1>Peta wisata</h1><p>Semua destinasi wisata, tidak hanya yang unggulan.</p>
<div class="tools">
    <button type="button" class="chip {{ $kategori === 'Semua' ? 'on' : '' }}" wire:click="pilihKategori('Semua')">Semua</button>
    @foreach ($daftarKategori as $k)
        <button type="button" class="chip {{ $kategori === $k->nama ? 'on' : '' }}" wire:key="kat-{{ $k->id }}" wire:click="pilihKategori('{{ $k->nama }}')">{{ $k->nama }}</button>
    @endforeach
</div>

@if ($daftar->isEmpty())
    <p style="color:var(--mut)">Belum ada destinasi{{ $kategori !== 'Semua' ? ' pada kategori '.$kategori : '' }}.</p>
@else
<div class="mapw">
<div class="lst">
    @foreach ($daftar as $d)
        <button type="button" class="it {{ $terpilih?->id === $d->id ? 'on' : '' }}" wire:key="it-{{ $d->id }}" wire:click="pilih({{ $d->id }})">
            <b>{{ $d->nama }}</b><small>{{ $d->kategori?->nama }} · Kec. {{ $d->kecamatan?->nama }}</small>
        </button>
    @endforeach
</div>

<div class="map" style="min-height:520px">
    {{-- Peta Leaflet digambar oleh JavaScript, jadi Livewire jangan menyentuh isinya --}}
    <div id="peta" wire:ignore style="position:absolute;inset:0"></div>

    @if ($terpilih)
    <div class="mc" style="z-index:1000">
        <span class="bd">{{ $terpilih->kategori?->nama }}</span>
        @if ($terpilih->unggulan) <span class="bd">Unggulan</span> @endif
        <br><b>{{ $terpilih->nama }}</b>
        @if ($terpilih->narasi)<p>{{ \Illuminate\Support\Str::limit($terpilih->narasi, 180) }}</p>@endif
        <p>{{ $terpilih->alamat }}, Kec. {{ $terpilih->kecamatan?->nama }}</p>
        <p>Koordinat: {{ number_format($terpilih->latitude, 4, '.', '') }}, {{ number_format($terpilih->longitude, 4, '.', '') }}
            · <a href="https://www.google.com/maps?q={{ $terpilih->latitude }},{{ $terpilih->longitude }}" target="_blank" rel="noopener" style="color:var(--pinkink);font-weight:600">Buka di Google Maps</a></p>
    </div>
    @endif
</div>
</div>
@endif
</div>

@assets
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<style>
    /* .map svg di publik.css bisa mengganggu ikon svg milik Leaflet */
    .map .leaflet-container svg { position: static; width: auto; height: auto; inset: auto; }
    .map .leaflet-pane svg, .map .leaflet-overlay-pane svg { position: absolute; }
    .map .leaflet-container { font: inherit; background: var(--bg2); }
    .pin-l { font-size: 26px; line-height: 1; text-align: center; filter: drop-shadow(0 2px 2px rgba(0,0,0,.3)); cursor: pointer; }
    .pin-l.on { font-size: 34px; }
    .leaflet-control-attribution a { color: var(--pinkink); }
</style>
@endassets

@script
<script>
    const el = document.getElementById('peta');
    if (el && window.L) {
        const PUSAT_JEMBER = [-8.17, 113.70];

        const peta = L.map(el, { attributionControl: false, scrollWheelZoom: true }).setView(PUSAT_JEMBER, 10);

        // Atribusi di kanan-atas supaya tidak tertutup kartu info di bawah peta.
        L.control.attribution({
            position: 'topright',
            prefix: '<a href="https://leafletjs.com" target="_blank" rel="noopener">Leaflet</a>',
        }).addTo(peta);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>',
        }).addTo(peta);

        const lapisPenanda = L.layerGroup().addTo(peta);

        function ikon(aktif) {
            return L.divIcon({
                className: 'pin-l' + (aktif ? ' on' : ''),
                html: '📍',
                iconSize: aktif ? [38, 38] : [30, 30],
                iconAnchor: aktif ? [19, 38] : [15, 30],
            });
        }

        function gambar(titik, terpilih, sesuaikanZoom) {
            lapisPenanda.clearLayers();

            const koordinat = [];
            let aktif = null;

            titik.forEach(function (t) {
                const posisi = [t.lat, t.lng];
                koordinat.push(posisi);

                const adalahTerpilih = t.id === terpilih;
                const penanda = L.marker(posisi, {
                    icon: ikon(adalahTerpilih),
                    title: t.nama,
                    zIndexOffset: adalahTerpilih ? 1000 : 0,
                    keyboard: true,
                }).addTo(lapisPenanda);

                penanda.bindTooltip(t.nama, { direction: 'top', offset: [0, -28] });
                penanda.on('click', function () { $wire.pilih(t.id); });

                if (adalahTerpilih) aktif = posisi;
            });

            if (sesuaikanZoom) {
                if (koordinat.length > 1) {
                    peta.fitBounds(L.latLngBounds(koordinat), { padding: [50, 50], maxZoom: 13 });
                } else if (koordinat.length === 1) {
                    peta.setView(koordinat[0], 13);
                }
            } else if (aktif) {
                peta.panTo(aktif, { animate: true });
            }
        }

        // Gambar awal: seluruh penanda + zoom menyesuaikan.
        gambar(@js($titik), @js($terpilih?->id), true);

        // Dipanggil setiap kali kategori / pilihan berubah (lihat PetaWisata.php).
        $wire.on('peta-diperbarui', function (e) {
            const d = Array.isArray(e) ? e[0] : e;
            gambar(d.titik || [], d.terpilih, !!d.sesuaikanZoom);
        });

        // Pastikan ukuran peta benar setelah layout selesai.
        setTimeout(function () { peta.invalidateSize(); }, 150);
    }
</script>
@endscript
</div>
