<div>
    <x-admin.topbar judul="Destinasi Wisata" sub="Kelola destinasi, titik peta, dan galeri" />

    @if (session('pesan'))
        <div class="alert ok" role="status">{{ session('pesan') }}</div>
    @endif

    <div class="bar-tools">
        <input type="search" wire:model.live.debounce.300ms="cari" placeholder="Cari destinasi wisata…" aria-label="Cari destinasi">
        <a class="btn p" href="{{ route('admin.destinasi-form') }}" wire:navigate>+ Tambah destinasi</a>
    </div>

    <div class="tw">
        <table>
            <thead>
                <tr>
                    <th>Destinasi</th>
                    <th>Kategori</th>
                    <th>Alamat</th>
                    <th>Koordinat</th>
                    <th>Unggulan</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftar as $d)
                    <tr wire:key="dest-{{ $d->id }}">
                        <td>
                            {{ $d->nama }}
                            @if ($d->status === 'draf') <span class="bd warn">Draf</span> @endif
                        </td>
                        <td><span class="bd g">{{ $d->kategori?->nama }}</span></td>
                        <td>{{ $d->alamat }}, Kec. {{ $d->kecamatan?->nama }}</td>
                        <td>{{ number_format($d->latitude, 4, '.', '') }}, {{ number_format($d->longitude, 4, '.', '') }}</td>
                        <td>
                            <button type="button" class="tg {{ $d->unggulan ? 'on' : '' }}"
                                    wire:click="toggleUnggulan({{ $d->id }})"
                                    aria-label="Unggulan" aria-pressed="{{ $d->unggulan ? 'true' : 'false' }}"></button>
                        </td>
                        <td>
                            <div class="act">
                                <a class="btn s" href="{{ route('admin.destinasi-ubah', $d) }}" wire:navigate>Ubah</a>
                                <button type="button" class="btn s d"
                                        wire:click="hapus({{ $d->id }})"
                                        wire:confirm="Hapus destinasi &quot;{{ $d->nama }}&quot;? Destinasi ini juga hilang dari halaman publik.">Hapus</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">
                        @if ($cari !== '') Tidak ada destinasi yang cocok dengan "{{ $cari }}".
                        @else Belum ada destinasi. Klik "+ Tambah destinasi" untuk mulai.
                        @endif
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pg">
        <span>Menampilkan {{ $daftar->count() }} dari {{ $daftar->total() }} data</span>
        <span class="pgn">
            <button type="button" class="btn s" wire:click="previousPage" @disabled($daftar->onFirstPage())>‹ Sebelumnya</button>
            Halaman {{ $daftar->currentPage() }} dari {{ max($daftar->lastPage(), 1) }}
            <button type="button" class="btn s" wire:click="nextPage" @disabled(! $daftar->hasMorePages())>Berikutnya ›</button>
        </span>
    </div>
</div>
