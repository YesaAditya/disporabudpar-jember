@props(['judul', 'sub' => ''])
<div class="top">
    <button class="btn burger" id="bg" aria-label="Menu">☰</button>
    <div><h1>{{ $judul }}</h1>@if ($sub)<p>{{ $sub }}</p>@endif</div>
    <div class="sp"></div>
    <button class="btn s" id="th" aria-label="Ganti tema">◐</button>
    <div class="me">
        <div class="av">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
        <span>{{ auth()->user()->name ?? 'Admin' }}</span>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="btn s">Keluar</button>
    </form>
</div>
