<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinasi_galeri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destinasi_id');
            $table->string('gambar', 255);
            $table->string('keterangan', 200)->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['destinasi_id', 'urutan'], 'idx_galeri_destinasi');
            $table->foreign('destinasi_id', 'fk_galeri_destinasi')->references('id')->on('destinasi')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinasi_galeri');
    }
};
