<div>
<div class="page art">
    <a class="chip" href="{{ route('publik.berita') }}" wire:navigate>← Semua berita</a>

    @if ($berita->status === 'draf' || $berita->terjadwal)
        <p style="margin-top:14px;padding:10px 14px;border:1px solid var(--line);border-radius:10px;background:var(--pink2);color:var(--pinkink);font-weight:600">Pratinjau admin: berita ini {{ $berita->status === 'draf' ? 'masih draf' : 'terjadwal' }} dan belum tampil di publik.</p>
    @endif

    <h1 style="margin-top:16px;line-height:1.2">{{ $berita->judul }}</h1>
    <p class="meta">{{ $berita->tanggal_teks }}{{ $berita->tanggal_terbit ? ' · ' : '' }}{{ $berita->kategori?->nama }} · dibaca {{ number_format($berita->jumlah_dibaca, 0, ',', '.') }} kali</p>

    <div class="ph" style="background:{{ $berita->gambar_url ? 'url('.$berita->gambar_url.') center/cover no-repeat' : $berita->gradien }}">@unless ($berita->gambar_url)📰@endunless</div>

    @foreach ($berita->paragraf as $p)
        <p>{!! nl2br(e($p)) !!}</p>
    @endforeach
</div>
</div>
