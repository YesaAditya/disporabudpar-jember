<?php

namespace App\Livewire\Admin;

use App\Models\Destinasi;
use App\Models\KategoriDestinasi;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Form Destinasi · Admin Disporabudpar Jember')]
class DestinasiForm extends Component
{
    use WithFileUploads;

    public ?int $destinasiId = null;

    public string $nama = '';

    public ?int $kategori_id = null;

    public ?int $kecamatan_id = null;

    public string $narasi = '';

    public string $alamat = '';

    public string $latitude = '';

    public string $longitude = '';

    public string $status = 'tayang';

    public bool $unggulan = false;

    public ?string $gambarLama = null;

    public $gambarBaru = null;

    public function mount(?Destinasi $destinasi = null): void
    {
        if ($destinasi && $destinasi->exists) {
            $this->destinasiId = $destinasi->id;
            $this->nama = $destinasi->nama;
            $this->kategori_id = $destinasi->kategori_id;
            $this->kecamatan_id = $destinasi->kecamatan_id;
            $this->narasi = (string) $destinasi->narasi;
            $this->alamat = $destinasi->alamat;
            $this->latitude = (string) $destinasi->latitude;
            $this->longitude = (string) $destinasi->longitude;
            $this->status = $destinasi->status;
            $this->unggulan = $destinasi->unggulan;
            $this->gambarLama = $destinasi->gambar_utama;
        }
    }

    protected function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'kategori_id' => ['required', 'exists:kategori_destinasi,id'],
            'kecamatan_id' => ['required', 'exists:kecamatan,id'],
            'narasi' => ['nullable', 'string', 'max:5000'],
            'alamat' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'status' => ['required', 'in:tayang,draf'],
            'unggulan' => ['boolean'],
            'gambarBaru' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    protected function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max.string' => ':attribute maksimal :max karakter.',
            'max.file' => ':attribute maksimal 2 MB.',
            'numeric' => ':attribute harus berupa angka (contoh -8.2151).',
            'between' => ':attribute harus di antara :min dan :max.',
            'exists' => ':attribute tidak valid.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat JPG, PNG, atau WEBP.',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'nama' => 'Nama destinasi',
            'kategori_id' => 'Kategori',
            'kecamatan_id' => 'Kecamatan',
            'narasi' => 'Narasi',
            'alamat' => 'Alamat',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'status' => 'Status',
            'gambarBaru' => 'Gambar utama',
        ];
    }

    public function simpan()
    {
        // Izinkan koma desimal (-8,2151) dan hilangkan spasi.
        $this->latitude = str_replace(',', '.', trim($this->latitude));
        $this->longitude = str_replace(',', '.', trim($this->longitude));

        $data = $this->validate();

        $atribut = [
            'kategori_id' => $data['kategori_id'],
            'kecamatan_id' => $data['kecamatan_id'],
            'nama' => trim($data['nama']),
            'narasi' => $data['narasi'] !== '' ? $data['narasi'] : null,
            'alamat' => trim($data['alamat']),
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'status' => $data['status'],
            'unggulan' => $data['unggulan'],
        ];

        if ($this->gambarBaru) {
            $atribut['gambar_utama'] = $this->gambarBaru->store('destinasi', 'public');
        }

        if ($this->destinasiId) {
            $destinasi = Destinasi::findOrFail($this->destinasiId);

            if ($this->gambarBaru && $destinasi->gambar_utama) {
                Storage::disk('public')->delete($destinasi->gambar_utama);
            }

            $destinasi->update($atribut);
            $pesan = "Destinasi \"{$destinasi->nama}\" diperbarui.";
        } else {
            $atribut['slug'] = $this->buatSlug($atribut['nama']);
            $atribut['dibuat_oleh'] = auth()->id();

            $destinasi = Destinasi::create($atribut);
            $pesan = "Destinasi \"{$destinasi->nama}\" ditambahkan.";
        }

        session()->flash('pesan', $pesan.($destinasi->status === 'draf' ? ' Status masih draf, belum tampil di publik.' : ''));

        return $this->redirectRoute('admin.destinasi', navigate: true);
    }

    public function hapusGambar(): void
    {
        $this->gambarBaru = null;
    }

    private function buatSlug(string $nama): string
    {
        $dasar = Str::slug($nama) ?: 'destinasi';
        $slug = $dasar;
        $i = 2;

        // withTrashed: indeks unik slug juga berlaku untuk data yang sudah dihapus.
        while (Destinasi::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $dasar.'-'.$i++;
        }

        return $slug;
    }

    public function render()
    {
        return view('livewire.admin.destinasi-form', [
            'daftarKategori' => KategoriDestinasi::orderBy('nama')->get(),
            'daftarKecamatan' => Kecamatan::orderBy('nama')->get(),
        ]);
    }
}
