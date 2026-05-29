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
        
        Schema::create('t_data', function (Blueprint $table) {
            $table->bigIncrements('id_data'); 
            $table->string('kode_data', 200)->nullable();
            $table->string('tipe_data', 200)->nullable();
            $table->string('kategori_data', 200)->nullable();
            $table->integer('topik_data')->nullable();
            $table->integer('organisasi_data')->nullable();
            $table->string('sifat_data', 200)->nullable();
            $table->text('deskripsi_data')->nullable();
            $table->string('frekuensi_data', 200)->nullable();
            $table->string('file_data', 255)->nullable();
            $table->string('tipe_file_data', 255)->nullable();
            $table->text('url_data')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_data');
    }
};
