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
        Schema::create('t_permohonan', function (Blueprint $table) {
            $table->bigIncrements('id_permohonan'); 
            $table->string('kode_data_permohonan',255)->nullable();
            $table->integer('data_id_permohonan')->nullable();
            $table->integer('user_id_permohonan')->nullable();
            $table->string('nama_permohonan', 255)->nullable();
            $table->string('email_permohonan', 255)->nullable();
            $table->string('identitas_permohonan', 255)->nullable();
            $table->string('file_identitas_permohonan', 255)->nullable();

            $table->string('telepon_permohonan', 255)->nullable();
            $table->string('pekerjaan_permohonan', 255)->nullable();
            $table->text('alamat_permohonan')->nullable();
            $table->string('status_permohonan', 20)->nullable();

            $table->integer('user_validasi_permohonan')->nullable();
            $table->text('catatan_validasi_permohonan')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_permohonan');
    }
};
