<?php

use App\Http\Controllers\Admin\SesiController;
use App\Livewire\Admin;
use App\Livewire\Publik;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/publik/beranda');

// ADMIN: semua alamat diawali /admin. Belum login -> otomatis ke /admin/login.
Route::prefix('admin')->group(function () {
    Route::redirect('/', '/admin/dashboard');

    Route::get('/login', Admin\Login::class)->middleware('guest')->name('admin.login');
    Route::post('/logout', [SesiController::class, 'keluar'])->middleware('auth')->name('admin.logout');

    Route::middleware(['auth', 'admin.aktif'])->group(function () {
        Route::get('/dashboard', Admin\Dashboard::class)->name('admin.dashboard');

        Route::get('/destinasi', Admin\Destinasi::class)->name('admin.destinasi');
        Route::get('/destinasi/tambah', Admin\DestinasiForm::class)->name('admin.destinasi-form');
        Route::get('/destinasi/{destinasi}/ubah', Admin\DestinasiForm::class)->name('admin.destinasi-ubah');

        Route::get('/berita', Admin\Berita::class)->name('admin.berita');
        Route::get('/berita/tulis', Admin\BeritaForm::class)->name('admin.berita-form');
        Route::get('/berita/{berita}/ubah', Admin\BeritaForm::class)->name('admin.berita-ubah');
        // Route::get('/kunjungan-hotel', Admin\KunjunganHotel::class)->name('admin.kunjungan-hotel');
        // Route::get('/ekonomi-kreatif', Admin\EkonomiKreatif::class)->name('admin.ekonomi-kreatif');
        // Route::get('/kesenian', Admin\Kesenian::class)->name('admin.kesenian');
    });
});

// PUBLIK
Route::get('/publik/beranda', Publik\Beranda::class)->name('publik.beranda');
Route::get('/publik/peta-wisata', Publik\PetaWisata::class)->name('publik.peta-wisata');
// Route::get('/publik/kunjungan-wisata', Publik\KunjunganWisata::class)->name('publik.kunjungan-wisata');
// Route::get('/publik/kunjungan-hotel', Publik\KunjunganHotel::class)->name('publik.kunjungan-hotel');
// Route::get('/publik/data-kesenian', Publik\DataKesenian::class)->name('publik.data-kesenian');
// Route::get('/publik/data-ekraf', Publik\DataEkraf::class)->name('publik.data-ekraf');
Route::get('/publik/berita', Publik\Berita::class)->name('publik.berita');
Route::get('/publik/berita/{berita:slug}', Publik\DetailBerita::class)->name('publik.detail-berita');
// Route::get('/publik/profil', Publik\Profil::class)->name('publik.profil');
// Route::get('/publik/struktur-organisasi', Publik\StrukturOrganisasi::class)->name('publik.struktur-organisasi');
// Route::get('/publik/hubungi-kami', Publik\HubungiKami::class)->name('publik.hubungi-kami');
