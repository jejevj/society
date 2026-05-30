<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reff_menu', function (Blueprint $table) {
            $table->bigIncrements('id_menu');
            $table->string('nama_menu')->nullable();
            $table->string('jenis_menu', 50)->nullable();
            $table->string('kode_menu')->nullable();
            $table->string('icon_menu')->nullable();
            $table->integer('parent_menu')->nullable();
            $table->integer('urutan_menu')->nullable();
            $table->text('deskripsi_menu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reff_menu');
    }
};
