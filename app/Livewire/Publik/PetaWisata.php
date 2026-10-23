<?php

namespace App\Livewire\Publik;

use App\Models\Destinasi;
use App\Models\KategoriDestinasi;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.publik')]
#[Title('Peta Wisata · Disporabudpar Jember')]
class PetaWisata extends Component
{
    #[Url(as: 'kategori', except: 'Semua')]
    public string $kategori = 'Semua';

    #[Url(as: 'dipilih')]
    public ?int $dipilih = null;

    public function pilihKategori(string $nama): void
    {
        $this->kategori = $nama;
        $this->kirimKePeta(sesuaikanZoom: true);
    }

    public function pilih(int $id): void
    {
        $this->dipilih = $id;
        $this->kirimKePeta(sesuaikanZoom: false);
    }

    /** Destinasi tayang, difilter kategori yang sedang aktif. */
    private function daftarDestinasi(): Collection
    {
        return Destinasi::with(['kategori', 'kecamatan'])
            ->tayang()
            ->when($this->kategori !== 'Semua', fn ($q) => $q->whereHas('kategori', fn ($k) => $k->where('nama', $this->kategori)))
            ->orderBy('nama')
            ->get();
    }

    /** Kalau pilihan sebelumnya tidak ada di daftar (ganti kategori / sudah dihapus), pakai yang pertama. */
    private function cariTerpilih(Collection $daftar): ?Destinasi
    {
        return $daftar->firstWhere('id', $this->dipilih) ?? $daftar->first();
    }

    /** Data minimal yang dibutuhkan JavaScript Leaflet untuk menggambar penanda. */
    private function titikPeta(Collection $daftar): array
    {
        return $daftar->map(fn (Destinasi $d) => [
            'id' => $d->id,
            'nama' => $d->nama,
            'kategori' => $d->kategori?->nama,
            'lat' => $d->latitude,
            'lng' => $d->longitude,
        ])->values()->all();
    }

    /** Kabari peta (JavaScript) bahwa daftar penanda / pilihan berubah. */
    private function kirimKePeta(bool $sesuaikanZoom): void
    {
        $daftar = $this->daftarDestinasi();

        $this->dispatch('peta-diperbarui',
            titik: $this->titikPeta($daftar),
            terpilih: $this->cariTerpilih($daftar)?->id,
            sesuaikanZoom: $sesuaikanZoom,
        );
    }

    public function render()
    {
        $daftar = $this->daftarDestinasi();
        $terpilih = $this->cariTerpilih($daftar);

        return view('livewire.publik.peta-wisata', [
            'daftarKategori' => KategoriDestinasi::whereHas('destinasi', fn ($q) => $q->tayang())->orderBy('nama')->get(),
            'daftar' => $daftar,
            'terpilih' => $terpilih,
            'titik' => $this->titikPeta($daftar),
        ]);
    }
}
