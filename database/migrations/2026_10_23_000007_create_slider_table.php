<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slider', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150);
            $table->string('deskripsi', 300)->nullable();
            $table->string('gambar', 255);
            $table->string('tautan', 255)->nullable();
            $table->string('teks_tombol', 60)->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->date('mulai_tayang')->nullable();
            $table->date('selesai_tayang')->nullable();
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->timestamps();

            $table->index(['aktif', 'urutan'], 'idx_slider_tampil');
            $table->index('dibuat_oleh', 'idx_slider_dibuat_oleh');
            $table->foreign('dibuat_oleh', 'fk_slider_user')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slider');
    }
};
