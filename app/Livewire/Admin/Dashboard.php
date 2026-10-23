<?php

namespace App\Livewire\Admin;

use App\Models\Berita;
use App\Models\Destinasi;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Dashboard · Admin Disporabudpar Jember')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'jumlahDestinasi' => Destinasi::count(),
            'jumlahUnggulan' => Destinasi::unggulan()->count(),
            'jumlahBerita' => Berita::terbit()->count(),
            'jumlahDraf' => Berita::where('status', 'draf')->count(),
            'unggulan' => Destinasi::with(['kategori', 'kecamatan'])->unggulan()->latest('id')->limit(5)->get(),
        ]);
    }
}
