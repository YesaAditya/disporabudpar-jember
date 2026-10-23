<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriDestinasi extends Model
{
    protected $table = 'kategori_destinasi';

    public $timestamps = false;

    protected $fillable = ['nama', 'slug'];

    public function destinasi(): HasMany
    {
        return $this->hasMany(Destinasi::class, 'kategori_id');
    }

    /** Ikon kecil untuk kartu tanpa foto. */
    public function getIkonAttribute(): string
    {
        return match ($this->slug) {
            'pantai' => '🏖',
            'pegunungan' => '⛰',
            'agro' => '🌱',
            'museum' => '🏛',
            'desa-wisata' => '🏡',
            'kuliner' => '🍜',
            default => '📍',
        };
    }
}
