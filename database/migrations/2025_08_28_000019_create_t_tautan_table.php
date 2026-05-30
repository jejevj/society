<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_tautan', function (Blueprint $table) {
            $table->id('id_tautan');
            $table->string('nama_tautan', 255);
            $table->string('link_tautan', 255);
            $table->unsignedInteger('urutan_tautan')->default(0);
            $table->string('gambar_tautan', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_tautan');
    }
};
