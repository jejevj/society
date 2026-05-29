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
       Schema::create('reff_topik', function (Blueprint $table) {
            $table->bigIncrements('id_topik'); 
            $table->string('nama_topik', 255)->nullable();
            $table->string('kode_topik', 255)->nullable();
            $table->integer('urutan_topik')->nullable();
            $table->string('gambar_topik', 255)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reff_topik');
    }
};
