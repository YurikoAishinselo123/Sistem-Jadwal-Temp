<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('makuls', function (Blueprint $table) {
            $table->id();
            $table->string('kode_makul')->unique();
            $table->string('nama_makul')->unique();
            $table->unsignedTinyInteger('jumlah_sesi_teori')->default(0);
            $table->unsignedTinyInteger('jumlah_sesi_praktek')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('makuls');
    }
};
