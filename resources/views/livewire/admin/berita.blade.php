<div>
    <x-admin.topbar judul="Berita" sub="Tulis dan terbitkan berita" />

    @if (session('pesan'))
        <div class="alert ok" role="status">{{ session('pesan') }}</div>
    @endif

    <div class="bar-tools">
        <input type="search" wire:model.live.debounce.300ms="cari" placeholder="Cari berita…" aria-label="Cari berita">
        <select wire:model.live="status" aria-label="Filter status">
            <option value="">Semua status</option>
            <option value="terbit">Terbit</option>
            <option value="draf">Draf</option>
        </select>
        <a class="btn p" href="{{ route('admin.berita-form') }}" wire:navigate>+ Tulis berita</a>
    </div>

    <div class="tw">
        <table>
            <thead>
                <tr><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($daftar as $b)
                    <tr wire:key="berita-{{ $b->id }}">
                        <td>{{ $b->judul }}</td>
                        <td><span class="bd g">{{ $b->kategori?->nama }}</span></td>
                        <td>{{ $b->tanggal_terbit?->locale('id')->translatedFormat('j M Y') ?? '—' }}</td>
                        <td>
                            @if ($b->status === 'draf')
                                <span class="bd g">Draf</span>
                            @elseif ($b->terjadwal)
                                <span class="bd warn" style="margin-left:0">Terjadwal</span>
                            @else
                                <span class="bd">Terbit</span>
                            @endif
                        </td>
                        <td>
                            <div class="act">
                                <a class="btn s" href="{{ route('publik.detail-berita', $b) }}" target="_blank" rel="noopener">Lihat</a>
                                <a class="btn s" href="{{ route('admin.berita-ubah', $b) }}" wire:navigate>Ubah</a>
                                <button type="button" class="btn s d"
                                        wire:click="hapus({{ $b->id }})"
                                        wire:confirm="Hapus berita &quot;{{ $b->judul }}&quot;? Berita ini juga hilang dari halaman publik.">Hapus</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">
                        @if ($cari !== '' || $status !== '') Tidak ada berita yang cocok dengan pencarian.
                        @else Belum ada berita. Klik "+ Tulis berita" untuk mulai.
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
