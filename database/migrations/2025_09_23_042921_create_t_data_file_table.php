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
        Schema::create('t_master_data', function (Blueprint $table) {
            $table->bigIncrements('id_master_data'); 
            $table->string('kode_data_master',255)->nullable();
            $table->string('judul_master',255)->nullable();
            $table->text('deskripsi_master')->nullable();
            $table->integer('organisasi_master')->nullable();
            $table->string('tipe_master',255)->nullable();
            $table->string('kategori_master',255)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_master_data');
    }
};
