<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_sosial', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('platform', 40);
            $table->string('url', 255);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);

            $table->unique('platform', 'uq_sosmed_platform');
            $table->index(['aktif', 'urutan'], 'idx_sosmed_tampil');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_sosial');
    }
};
