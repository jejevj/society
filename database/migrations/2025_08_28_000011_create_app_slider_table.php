<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_slider', function (Blueprint $table) {
            $table->bigIncrements('id_slider');
            $table->string('judul_slider')->nullable();
            $table->text('deskripsi_slider')->nullable();
            $table->string('gambar_slider')->nullable();
            $table->integer('urutan_slider')->nullable();
            $table->string('jenis_slider', 20)->nullable()->comment('gambar / text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_slider');
    }
};
