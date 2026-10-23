<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('struktur_organisasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedSmallInteger('bidang_id')->nullable();
            $table->string('jabatan', 120);
            $table->string('nama_pejabat', 120)->nullable();
            $table->string('foto', 255)->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['parent_id', 'urutan'], 'idx_struktur_parent');
            $table->index('bidang_id', 'idx_struktur_bidang');
            $table->foreign('parent_id', 'fk_struktur_parent')->references('id')->on('struktur_organisasi')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('bidang_id', 'fk_struktur_bidang')->references('id')->on('bidang')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('struktur_organisasi');
    }
};
