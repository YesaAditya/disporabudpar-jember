<div class="login-card card">
    <div class="brand" style="padding:0 0 18px">
        <div class="logo">D</div>
        <div><b>Disporabudpar</b><small>Kabupaten Jember · Admin</small></div>
    </div>

    <h1 style="font-size:20px;margin:0 0 4px">Masuk</h1>
    <p style="margin:0 0 18px;color:var(--mut)">Halaman ini khusus pengelola situs.</p>

    <form wire:submit="masuk" class="login-form">
        <div class="fd">
            <label for="email">Email</label>
            <input id="email" type="email" wire:model="email" autocomplete="username" autofocus>
            @error('email') <small class="err-msg">{{ $message }}</small> @enderror
        </div>

        <div class="fd">
            <label for="password">Kata sandi</label>
            <input id="password" type="password" wire:model="password" autocomplete="current-password">
            @error('password') <small class="err-msg">{{ $message }}</small> @enderror
        </div>

        <label class="cek"><input type="checkbox" wire:model="ingat"> Ingat saya di perangkat ini</label>

        <button type="submit" class="btn p" wire:loading.attr="disabled" wire:target="masuk" style="width:100%">
            <span wire:loading.remove wire:target="masuk">Masuk</span>
            <span wire:loading wire:target="masuk">Memeriksa…</span>
        </button>
    </form>

    <p style="margin:16px 0 0;font-size:13px"><a href="{{ route('publik.beranda') }}" style="color:var(--pinkink);font-weight:600">← Kembali ke situs</a></p>
</div>
