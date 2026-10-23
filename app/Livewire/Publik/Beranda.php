<?php

namespace App\Livewire\Publik;

use App\Models\Berita;
use App\Models\Destinasi;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.publik')]
#[Title('Disporabudpar Jember')]
class Beranda extends Component
{
    public function render()
    {
        return view('livewire.publik.beranda', [
            // Hanya destinasi berstatus tayang + ditandai unggulan oleh admin.
            'unggulan' => Destinasi::with(['kategori', 'kecamatan'])
                ->tayang()->unggulan()
                ->latest('id')
                ->limit(6)
                ->get(),

            // Berita terbit (tanggal terbit sudah tiba), 3 terbaru.
            'berita' => Berita::with('kategori')
                ->terbit()
                ->latest('tanggal_terbit')
                ->limit(3)
                ->get(),
        ]);
    }
}
