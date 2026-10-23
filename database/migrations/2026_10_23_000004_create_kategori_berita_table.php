<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_berita', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nama', 60);
            $table->string('slug', 80);

            $table->unique('nama', 'uq_kat_berita_nama');
            $table->unique('slug', 'uq_kat_berita_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_berita');
    }
};
