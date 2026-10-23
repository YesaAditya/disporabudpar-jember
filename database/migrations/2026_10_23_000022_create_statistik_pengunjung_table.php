<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistik_pengunjung', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->unsignedInteger('jumlah')->default(0);

            $table->unique('tanggal', 'uq_statistik_tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistik_pengunjung');
    }
};
