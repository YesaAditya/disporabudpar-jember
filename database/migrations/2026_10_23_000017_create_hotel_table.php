<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('kecamatan_id');
            $table->string('nama', 150);
            $table->string('kelas', 30)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('nama', 'uq_hotel_nama');
            $table->index('kecamatan_id', 'idx_hotel_kecamatan');
            $table->foreign('kecamatan_id', 'fk_hotel_kecamatan')->references('id')->on('kecamatan')->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel');
    }
};
