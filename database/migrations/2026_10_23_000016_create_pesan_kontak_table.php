<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesan_kontak', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 120);
            $table->string('email', 150);
            $table->string('subjek', 150)->nullable();
            $table->text('pesan');
            $table->boolean('dibaca')->default(false);
            $table->timestamp('created_at')->nullable();

            $table->index(['dibaca', 'created_at'], 'idx_pesan_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan_kontak');
    }
};
