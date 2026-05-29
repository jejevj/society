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
        Schema::create('t_tautan', function (Blueprint $table) {
            $table->bigIncrements('id_tautan'); 
            $table->string('nama_tautan',255)->nullable();
            $table->string('link_tautan',255)->nullable();
            $table->string('gambar_tautan',255)->nullable();
            $table->integer('urutan_tautan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_tautan');
    }
};
