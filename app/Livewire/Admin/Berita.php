<?php

namespace App\Livewire\Admin;

use App\Models\Berita as BeritaModel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Berita · Admin Disporabudpar Jember')]
class Berita extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $cari = '';

    #[Url(as: 'status', except: '')]
    public string $status = '';

    public function updatedCari(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function hapus(int $id): void
    {
        $b = BeritaModel::findOrFail($id);
        $judul = $b->judul;
        $b->delete(); // soft delete

        session()->flash('pesan', "Berita \"{$judul}\" dihapus.");
    }

    public function render()
    {
        $daftar = BeritaModel::with('kategori')
            ->when($this->cari !== '', fn ($q) => $q->where('judul', 'like', '%'.trim($this->cari).'%'))
            ->when(in_array($this->status, ['terbit', 'draf'], true), fn ($q) => $q->where('status', $this->status))
            ->latest('id')
            ->paginate(10);

        return view('livewire.admin.berita', ['daftar' => $daftar]);
    }
}
