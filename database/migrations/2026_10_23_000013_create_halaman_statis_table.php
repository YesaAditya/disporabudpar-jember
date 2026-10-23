<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('halaman_statis', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug', 80);
            $table->string('judul', 150);
            $table->longText('isi')->nullable();
            $table->unsignedBigInteger('diubah_oleh')->nullable();
            $table->timestamps();

            $table->unique('slug', 'uq_halaman_slug');
            $table->index('diubah_oleh', 'idx_halaman_diubah_oleh');
            $table->foreign('diubah_oleh', 'fk_halaman_user')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('halaman_statis');
    }
};
