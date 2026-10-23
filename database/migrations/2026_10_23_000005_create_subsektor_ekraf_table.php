<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subsektor_ekraf', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nama', 80);

            $table->unique('nama', 'uq_subsektor_nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subsektor_ekraf');
    }
};
