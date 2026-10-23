<div>
<div class="page"><h1>Berita</h1><p>Kabar terbaru dari Disporabudpar Jember.</p>
<div class="tools">
    <button type="button" class="chip {{ $kategori === 'Semua' ? 'on' : '' }}" wire:click="pilihKategori('Semua')">Semua</button>
    @foreach ($daftarKategori as $k)
        <button type="button" class="chip {{ $kategori === $k->nama ? 'on' : '' }}" wire:key="kat-{{ $k->id }}" wire:click="pilihKategori('{{ $k->nama }}')">{{ $k->nama }}</button>
    @endforeach
</div>

<div class="news">
@forelse ($daftar as $b)
    <a class="c" wire:key="b-{{ $b->id }}" href="{{ route('publik.detail-berita', $b) }}" wire:navigate>
        <div class="ph s" style="background:{{ $b->gambar_url ? 'url('.$b->gambar_url.') center/cover no-repeat' : $b->gradien }}">@unless ($b->gambar_url)📰@endunless</div>
        <div class="cb"><span class="bd">{{ $b->kategori?->nama }}</span><b>{{ $b->judul }}</b><small>{{ $b->tanggal_teks }} · dibaca {{ number_format($b->jumlah_dibaca, 0, ',', '.') }} kali</small></div>
    </a>
@empty
    <p style="color:var(--mut)">Belum ada berita{{ $kategori !== 'Semua' ? ' pada kategori '.$kategori : '' }}.</p>
@endforelse
</div>

@if ($daftar->hasPages())
<div class="tools" style="justify-content:center;margin-top:20px">
    <button type="button" class="chip" wire:click="previousPage" @disabled($daftar->onFirstPage())>‹ Sebelumnya</button>
    <span style="color:var(--mut)">Halaman {{ $daftar->currentPage() }} dari {{ $daftar->lastPage() }}</span>
    <button type="button" class="chip" wire:click="nextPage" @disabled(! $daftar->hasMorePages())>Berikutnya ›</button>
</div>
@endif
</div>
</div>
