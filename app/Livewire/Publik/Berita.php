<?php

namespace App\Livewire\Publik;

use App\Models\Berita as BeritaModel;
use App\Models\KategoriBerita;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.publik')]
#[Title('Berita · Disporabudpar Jember')]
class Berita extends Component
{
    use WithPagination;

    #[Url(as: 'kategori', except: 'Semua')]
    public string $kategori = 'Semua';

    public function pilihKategori(string $nama): void
    {
        $this->kategori = $nama;
        $this->resetPage();
    }

    public function render()
    {
        $daftar = BeritaModel::with('kategori')
            ->terbit()
            ->when($this->kategori !== 'Semua', fn ($q) => $q->whereHas('kategori', fn ($k) => $k->where('nama', $this->kategori)))
            ->latest('tanggal_terbit')
            ->paginate(9);

        return view('livewire.publik.berita', [
            'daftar' => $daftar,
            'daftarKategori' => KategoriBerita::orderBy('nama')->get(),
        ]);
    }
}
