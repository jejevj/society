<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reff_organisasi', function (Blueprint $table) {
            $table->bigIncrements('id_organisasi');
            $table->string('kode_organisasi')->nullable();
            $table->string('nama_organisasi')->nullable();
            $table->string('singkatan_organisasi')->nullable();
            $table->string('web_organisasi')->nullable();
            $table->string('foto_organisasi')->nullable();
            $table->string('tmp_foto_organisasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reff_orgainisasis');
    }
};
