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
        Schema::create('t_pengaduan', function (Blueprint $table) {
            $table->bigIncrements('id_pengaduan'); 
            $table->integer('user_id_pengaduan')->nullable();
            $table->string('nama_pengaduan', 255)->nullable();
            $table->string('email_pengaduan', 255)->nullable();
            $table->string('nik_pengaduan', 20)->nullable();
            $table->string('ip_pengaduan', 200)->nullable();
            $table->string('status_pengaduan', 20)->nullable();
            $table->string('capcha_pengaduan', 100)->nullable();
            $table->integer('validasi_pengaduan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pengaduan');
    }
};
