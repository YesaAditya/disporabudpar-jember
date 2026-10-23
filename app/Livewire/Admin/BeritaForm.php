<?php

namespace App\Livewire\Admin;

use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Form Berita · Admin Disporabudpar Jember')]
class BeritaForm extends Component
{
    use WithFileUploads;

    public ?int $beritaId = null;

    public string $judul = '';

    public ?int $kategori_berita_id = null;

    public string $tanggal = '';

    public string $status = 'terbit';

    public string $ringkasan = '';

    public string $isi = '';

    public ?string $gambarLama = null;

    public $gambarBaru = null;

    public function mount(?Berita $berita = null): void
    {
        if ($berita && $berita->exists) {
            $this->beritaId = $berita->id;
            $this->judul = $berita->judul;
            $this->kategori_berita_id = $berita->kategori_berita_id;
            $this->tanggal = $berita->tanggal_terbit?->format('Y-m-d') ?? '';
            $this->status = $berita->status;
            $this->ringkasan = (string) $berita->ringkasan;
            $this->isi = $berita->isi;
            $this->gambarLama = $berita->gambar_sampul;
        } else {
            $this->tanggal = now()->format('Y-m-d');
        }
    }

    protected function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'kategori_berita_id' => ['required', 'exists:kategori_berita,id'],
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
            'status' => ['required', 'in:terbit,draf'],
            'ringkasan' => ['nullable', 'string', 'max:300'],
            'isi' => ['required', 'string', 'max:100000'],
            'gambarBaru' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    protected function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max.string' => ':attribute maksimal :max karakter.',
            'max.file' => ':attribute maksimal 2 MB.',
            'date_format' => ':attribute tidak valid.',
            'exists' => ':attribute tidak valid.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat JPG, PNG, atau WEBP.',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'judul' => 'Judul',
            'kategori_berita_id' => 'Kategori',
            'tanggal' => 'Tanggal terbit',
            'status' => 'Status',
            'ringkasan' => 'Ringkasan',
            'isi' => 'Isi berita',
            'gambarBaru' => 'Gambar sampul',
        ];
    }

    public function simpan()
    {
        $data = $this->validate();
        $berita = $this->beritaId ? Berita::findOrFail($this->beritaId) : null;

        // Tanggal terbit: pakai tanggal yang dipilih; kalau kosong dan status terbit, pakai sekarang.
        $tanggalTerbit = null;
        if ($data['tanggal'] !== null && $data['tanggal'] !== '') {
            $dipilih = Carbon::createFromFormat('Y-m-d', $data['tanggal'])->startOfDay();
            // Hari yang sama dengan data lama: pertahankan jam aslinya.
            $tanggalTerbit = ($berita?->tanggal_terbit && $berita->tanggal_terbit->isSameDay($dipilih))
                ? $berita->tanggal_terbit
                : $dipilih;
        } elseif ($data['status'] === 'terbit') {
            $tanggalTerbit = $berita?->tanggal_terbit ?? now();
        }

        $atribut = [
            'kategori_berita_id' => $data['kategori_berita_id'],
            'judul' => trim($data['judul']),
            'ringkasan' => ($data['ringkasan'] ?? '') !== '' ? trim($data['ringkasan']) : null,
            'isi' => $data['isi'],
            'status' => $data['status'],
            'tanggal_terbit' => $tanggalTerbit,
        ];

        if ($this->gambarBaru) {
            $atribut['gambar_sampul'] = $this->gambarBaru->store('berita', 'public');
        }

        if ($berita) {
            if ($this->gambarBaru && $berita->gambar_sampul) {
                Storage::disk('public')->delete($berita->gambar_sampul);
            }

            $berita->update($atribut);
            $pesan = "Berita \"{$berita->judul}\" diperbarui.";
        } else {
            $atribut['slug'] = $this->buatSlug($atribut['judul']);
            $atribut['penulis_id'] = auth()->id();

            $berita = Berita::create($atribut);
            $pesan = "Berita \"{$berita->judul}\" disimpan.";
        }

        if ($berita->status === 'draf') {
            $pesan .= ' Status masih draf, belum tampil di publik.';
        } elseif ($berita->terjadwal) {
            $pesan .= ' Tanggal terbit di masa depan, tampil di publik mulai tanggal tersebut.';
        }

        session()->flash('pesan', $pesan);

        return $this->redirectRoute('admin.berita', navigate: true);
    }

    public function hapusGambar(): void
    {
        $this->gambarBaru = null;
    }

    private function buatSlug(string $judul): string
    {
        $dasar = Str::limit(Str::slug($judul), 200, '') ?: 'berita';
        $slug = $dasar;
        $i = 2;

        while (Berita::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $dasar.'-'.$i++;
        }

        return $slug;
    }

    public function render()
    {
        return view('livewire.admin.berita-form', [
            'daftarKategori' => KategoriBerita::orderBy('nama')->get(),
        ]);
    }
}
