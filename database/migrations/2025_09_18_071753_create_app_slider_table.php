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
        Schema::create('app_slider', function (Blueprint $table) {
            $table->bigIncrements('id_slider'); 
            $table->string('gambar_slider',255)->nullable();
            $table->string('judul_slider',255)->nullable();
            $table->integer('urutan_slider')->nullable();
            $table->text('deskripsi_slider')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_slider');
    }
};
