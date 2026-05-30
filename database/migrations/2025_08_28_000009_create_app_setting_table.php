<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_setting', function (Blueprint $table) {
            $table->bigIncrements('id_setting');
            $table->string('logo')->nullable();
            $table->string('gambar_dashboard')->nullable();
            $table->string('gambar_topik')->nullable();
            $table->text('deskripsi_topik')->nullable();
            $table->string('gambar_organisasi')->nullable();
            $table->text('deskripsi_organisasi')->nullable();
            $table->string('gambar_permohonan')->nullable();
            $table->text('deskripsi_permohonan')->nullable();
            $table->string('gambar2_permohonan')->nullable();
            $table->string('gambar_hubungi')->nullable();
            $table->text('deskripsi_hubungi')->nullable();
            $table->string('gambar2_hubungi')->nullable();
            $table->string('gambar_tentang')->nullable();
            $table->text('deskripsi_tentang')->nullable();
            $table->string('gambar2_tentang')->nullable();
            $table->string('gambar_login')->nullable();
            $table->text('deskripsi_login')->nullable();
            $table->string('gambar2_login')->nullable();
            $table->text('url_facebook')->nullable();
            $table->text('url_twitter')->nullable();
            $table->text('url_instagram')->nullable();
            $table->text('url_youtube')->nullable();
            $table->text('url_linkedin')->nullable();
            $table->string('kode', 10)->nullable();
            $table->char('cek_antivirus', 1)->default('Y');
            $table->string('url_antivirus')->nullable();
            $table->string('url_chatbot')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_setting');
    }
};
