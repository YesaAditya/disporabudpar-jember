<?php

namespace App\Livewire\Admin;

use App\Models\Destinasi as DestinasiModel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Destinasi Wisata · Admin Disporabudpar Jember')]
class Destinasi extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $cari = '';

    public function updatedCari(): void
    {
        $this->resetPage();
    }

    public function toggleUnggulan(int $id): void
    {
        $d = DestinasiModel::findOrFail($id);
        $d->update(['unggulan' => ! $d->unggulan]);
    }

    public function hapus(int $id): void
    {
        $d = DestinasiModel::findOrFail($id);
        $nama = $d->nama;
        $d->delete(); // soft delete: hilang dari admin dan publik, data masih ada di database

        session()->flash('pesan', "Destinasi \"{$nama}\" dihapus.");
    }

    public function render()
    {
        $destinasi = DestinasiModel::with(['kategori', 'kecamatan'])
            ->when($this->cari !== '', function ($q) {
                $kata = '%'.trim($this->cari).'%';
                $q->where(fn ($w) => $w->where('nama', 'like', $kata)->orWhere('alamat', 'like', $kata));
            })
            ->latest('id')
            ->paginate(10);

        return view('livewire.admin.destinasi', ['daftar' => $destinasi]);
    }
}
