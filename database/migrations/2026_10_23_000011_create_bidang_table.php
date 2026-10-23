<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bidang', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nama', 120);
            $table->string('slug', 140);
            $table->enum('jenis', ['bidang', 'uptd'])->default('bidang');
            $table->text('deskripsi')->nullable();
            $table->string('ikon', 60)->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();

            $table->unique('slug', 'uq_bidang_slug');
            $table->index(['jenis', 'urutan'], 'idx_bidang_jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bidang');
    }
};
