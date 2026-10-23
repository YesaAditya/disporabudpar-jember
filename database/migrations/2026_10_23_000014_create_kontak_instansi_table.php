<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontak_instansi', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nama_instansi', 200);
            $table->string('alamat', 255);
            $table->string('telepon', 40)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('jam_layanan', 150)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontak_instansi');
    }
};
