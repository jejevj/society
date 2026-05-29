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
        Schema::create('app_setting', function (Blueprint $table) {
            $table->bigIncrements('id_setting'); 
            $table->string('logo',255)->nullable();
            $table->string('gambar_dashboard',255)->nullable();
            $table->string('gambar_topik',255)->nullable();
            $table->text('deskripsi_topik')->nullable();
            $table->string('gambar_organisasi',255)->nullable();
            $table->text('deskripsi_organisasi')->nullable();
            $table->string('gambar_permohonan',255)->nullable();
            $table->text('deskripsi_permohonan')->nullable();
            $table->string('gambar2_permohonan',255)->nullable();
            $table->string('gambar_hubungi',255)->nullable();
            $table->text('deskripsi_hubungi')->nullable();
            $table->string('gambar2_hubungi',255)->nullable();
            $table->string('gambar_tentang',255)->nullable();
            $table->text('deskripsi_tentang')->nullable();
            $table->string('gambar2_tentang',255)->nullable();
            $table->string('gambar_login',255)->nullable();
            $table->text('deskripsi_login')->nullable();
            $table->string('gambar2_login',255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_setting');
    }
};
