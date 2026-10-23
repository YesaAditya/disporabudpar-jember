<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destinasi extends Model
{
    use SoftDeletes;

    protected $table = 'destinasi';

    protected $fillable = [
        'kategori_id', 'kecamatan_id', 'nama', 'slug', 'narasi', 'alamat',
        'latitude', 'longitude', 'gambar_utama', 'unggulan', 'status', 'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'unggulan' => 'boolean',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriDestinasi::class, 'kategori_id');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /** Hanya yang berstatus "tayang" boleh muncul di halaman publik. */
    public function scopeTayang(Builder $query): Builder
    {
        return $query->where('status', 'tayang');
    }

    public function scopeUnggulan(Builder $query): Builder
    {
        return $query->where('unggulan', true);
    }

    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar_utama ? asset('storage/'.$this->gambar_utama) : null;
    }

    /** Gradien cadangan (dipakai kartu publik kalau belum ada foto). */
    public function getGradienAttribute(): string
    {
        $daftar = [
            'linear-gradient(135deg,#E8487F,#F7A1C0)',
            'linear-gradient(135deg,#C93A74,#F28BB0)',
            'linear-gradient(135deg,#F06A9A,#FBC4D8)',
        ];

        return $daftar[$this->id % count($daftar)];
    }
}
