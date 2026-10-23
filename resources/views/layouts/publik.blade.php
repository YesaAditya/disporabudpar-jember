@php
    // Menu otomatis: item yang route-nya belum dibuat disembunyikan, jadi tidak pernah error.
    $menu = [
        ['Beranda', 'publik.beranda'],
        ['Wisata', [
            ['Peta Wisata', 'publik.peta-wisata'],
            ['Kunjungan Wisata', 'publik.kunjungan-wisata'],
            ['Kunjungan Hotel', 'publik.kunjungan-hotel'],
        ]],
        ['Budaya & Ekraf', [
            ['Data Kesenian', 'publik.data-kesenian'],
            ['Data Ekraf', 'publik.data-ekraf'],
        ]],
        ['Berita', 'publik.berita'],
        ['Tentang Kami', [
            ['Profil', 'publik.profil'],
            ['Struktur Organisasi', 'publik.struktur-organisasi'],
            ['Hubungi Kami', 'publik.hubungi-kami'],
        ]],
    ];
    $ada = fn ($nama) => \Illuminate\Support\Facades\Route::has($nama);
    $aktif = fn ($nama) => request()->routeIs($nama) || request()->routeIs($nama.'.*');
@endphp
<!doctype html>
<html lang="id"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ $title ?? 'Disporabudpar Jember' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/publik.css') }}">
<script>try{var t=localStorage.getItem('publik-tema');if(t)document.documentElement.setAttribute('data-theme',t)}catch(e){}</script>
@livewireStyles
</head><body>

<header>
    <div class="w hb">
        <a class="logo" href="{{ route('publik.beranda') }}" wire:navigate>
            <span class="lg">J</span>
            <span>Disporabudpar<small>Kabupaten Jember</small></span>
        </a>
        <button type="button" class="bg" aria-label="Menu">☰</button>
        <nav>
            @foreach ($menu as [$label, $tujuan])
                @if (is_string($tujuan))
                    @if ($ada($tujuan))
                        <div class="ni"><a class="nb {{ $aktif($tujuan) ? 'on' : '' }}" href="{{ route($tujuan) }}" wire:navigate>{{ $label }}</a></div>
                    @endif
                @else
                    @php $anak = array_values(array_filter($tujuan, fn ($a) => $ada($a[1]))); @endphp
                    @if ($anak)
                        <div class="ni">
                            <button type="button" class="nb {{ collect($anak)->contains(fn ($a) => $aktif($a[1])) ? 'on' : '' }}" aria-haspopup="true">{{ $label }} ▾</button>
                            <div class="dd">
                                @foreach ($anak as [$l, $r])
                                    <a class="nb {{ $aktif($r) ? 'on' : '' }}" href="{{ route($r) }}" wire:navigate>{{ $l }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
            <button type="button" class="btn" id="th" aria-label="Ganti tema" style="padding:6px 12px">◐</button>
        </nav>
    </div>
</header>

<main class="w">{{ $slot }}</main>

<footer>
    <div class="w">
        <div class="fg">
            <div>
                <h4>Disporabudpar Kabupaten Jember</h4>
                <p>Dinas yang melayani urusan kepemudaan, olahraga, kebudayaan, dan pariwisata.</p>
            </div>
            <div>
                <h4>Jelajahi</h4>
                <p><a href="{{ route('publik.peta-wisata') }}" wire:navigate>Peta Wisata</a></p>
                @if ($ada('publik.berita'))<p><a href="{{ route('publik.berita') }}" wire:navigate>Berita</a></p>@endif
            </div>
            <div>
                <h4>Admin</h4>
                <p><a href="{{ route('admin.login') }}">Masuk admin</a></p>
            </div>
        </div>
        <div class="cp">© {{ date('Y') }} Disporabudpar Kabupaten Jember</div>
    </div>
</footer>

@livewireScripts
<script data-navigate-once>
(function () {
    var q = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

    // Klik: menu mobile, dropdown, ganti tema, titik slider (pakai delegasi supaya tetap jalan setelah wire:navigate)
    document.addEventListener('click', function (e) {
        var t = e.target;

        if (t.closest('.bg')) { q('header nav').forEach(function (n) { n.classList.toggle('on'); }); return; }

        if (t.closest('#th')) {
            var el = document.documentElement;
            var baru = el.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            el.setAttribute('data-theme', baru);
            try { localStorage.setItem('publik-tema', baru); } catch (x) {}
            return;
        }

        var dot = t.closest('.dots button');
        if (dot) { pindah(parseInt(dot.getAttribute('data-s'), 10) || 0); return; }

        var tombol = t.closest('.ni > button.nb');
        q('.ni.open').forEach(function (n) { if (!tombol || n !== tombol.parentNode) n.classList.remove('open'); });
        if (tombol) tombol.parentNode.classList.toggle('open');
    });

    // Tutup menu setelah pindah halaman
    document.addEventListener('livewire:navigated', function () {
        q('header nav').forEach(function (n) { n.classList.remove('on'); });
        q('.ni.open').forEach(function (n) { n.classList.remove('open'); });
    });

    // Slider beranda
    function pindah(i) {
        var s = q('#sl .sl'), d = q('#sl .dots button');
        if (!s.length) return;
        i = ((i % s.length) + s.length) % s.length;
        s.forEach(function (x, n) { x.classList.toggle('on', n === i); });
        d.forEach(function (x, n) { x.classList.toggle('on', n === i); });
    }
    setInterval(function () {
        var s = q('#sl .sl');
        if (s.length < 2 || document.hidden) return;
        var sekarang = s.findIndex(function (x) { return x.classList.contains('on'); });
        pindah(sekarang + 1);
    }, 5500);
})();
</script>
</body></html>
