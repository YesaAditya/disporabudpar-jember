<div>
    <x-admin.topbar :judul="$destinasiId ? 'Ubah destinasi' : 'Tambah destinasi'" sub="Data yang disimpan di sini langsung tampil di halaman publik (jika status Tayang)" />

    <form wire:submit="simpan" class="card" style="padding:0;max-width:760px">
        <div class="mb">
            <div class="fd">
                <label for="nama">Nama destinasi</label>
                <input id="nama" type="text" wire:model="nama">
                @error('nama') <small class="err-msg">{{ $message }}</small> @enderror
            </div>

            <div class="fd">
                <label for="kategori">Kategori</label>
                <select id="kategori" wire:model="kategori_id">
                    <option value="">Pilih kategori…</option>
                    @foreach ($daftarKategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
                @error('kategori_id') <small class="err-msg">{{ $message }}</small> @enderror
            </div>

            <div class="fd">
                <label for="kecamatan">Kecamatan</label>
                <select id="kecamatan" wire:model="kecamatan_id">
                    <option value="">Pilih kecamatan…</option>
                    @foreach ($daftarKecamatan as $kc)
                        <option value="{{ $kc->id }}">{{ $kc->nama }}</option>
                    @endforeach
                </select>
                @error('kecamatan_id') <small class="err-msg">{{ $message }}</small> @enderror
            </div>

            <div class="fd">
                <label for="status">Status</label>
                <select id="status" wire:model="status">
                    <option value="tayang">Tayang (tampil di publik)</option>
                    <option value="draf">Draf (disembunyikan)</option>
                </select>
                @error('status') <small class="err-msg">{{ $message }}</small> @enderror
            </div>

            <div class="fd full">
                <label for="narasi">Narasi</label>
                <textarea id="narasi" rows="4" wire:model="narasi"></textarea>
                @error('narasi') <small class="err-msg">{{ $message }}</small> @enderror
            </div>

            <div class="fd full">
                <label for="alamat">Alamat resmi</label>
                <input id="alamat" type="text" wire:model="alamat" placeholder="Contoh: Lojejer">
                @error('alamat') <small class="err-msg">{{ $message }}</small> @enderror
            </div>

            <div class="fd full">
                <label for="gambar">Gambar utama</label>
                <input id="gambar" type="file" wire:model="gambarBaru" accept="image/png,image/jpeg,image/webp">
                <small style="color:var(--mut)">JPG/PNG/WEBP, maksimal 2 MB.</small>
                <div wire:loading wire:target="gambarBaru" style="color:var(--pinkink)">Mengunggah…</div>
                @error('gambarBaru') <small class="err-msg">{{ $message }}</small> @enderror

                @if ($gambarBaru && ! $errors->has('gambarBaru'))
                    <div class="thumb"><img src="{{ $gambarBaru->temporaryUrl() }}" alt="Pratinjau gambar baru"></div>
                    <button type="button" class="btn s" style="align-self:flex-start" wire:click="hapusGambar">Batalkan gambar baru</button>
                @elseif ($gambarLama)
                    <div class="thumb"><img src="{{ asset('storage/'.$gambarLama) }}" alt="Gambar saat ini"></div>
                @endif
            </div>

            <div class="fd full">
                <label>Titik peta</label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div class="fd">
                        <label for="lat">Latitude</label>
                        <input id="lat" type="text" inputmode="decimal" wire:model="latitude" placeholder="-8.2151">
                        @error('latitude') <small class="err-msg">{{ $message }}</small> @enderror
                    </div>
                    <div class="fd">
                        <label for="lng">Longitude</label>
                        <input id="lng" type="text" inputmode="decimal" wire:model="longitude" placeholder="113.6662">
                        @error('longitude') <small class="err-msg">{{ $message }}</small> @enderror
                    </div>
                </div>
                <small style="color:var(--mut)">Wajib diisi. Tips: di Google Maps klik kanan pada lokasi, lalu klik angka koordinat untuk menyalin.</small>
            </div>

            <div class="fd full">
                <label>Tampilkan di beranda publik (unggulan)</label>
                <button type="button" class="tg {{ $unggulan ? 'on' : '' }}" wire:click="$toggle('unggulan')" aria-label="Unggulan" aria-pressed="{{ $unggulan ? 'true' : 'false' }}"></button>
            </div>
        </div>

        <div class="mf">
            <a class="btn" href="{{ route('admin.destinasi') }}" wire:navigate>Batal</a>
            <button type="submit" class="btn p" wire:loading.attr="disabled" wire:target="simpan,gambarBaru">
                <span wire:loading.remove wire:target="simpan">Simpan</span>
                <span wire:loading wire:target="simpan">Menyimpan…</span>
            </button>
        </div>
    </form>
</div>
