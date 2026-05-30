<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_event_registrasi', function (Blueprint $table) {
            $table->increments('id_registrasi');
            $table->string('kode_registrasi', 30)->unique();
            $table->string('kode_event', 20)->nullable();
            $table->string('nama_peserta', 255)->nullable();
            $table->string('email_peserta', 255)->nullable();
            $table->string('instansi_peserta', 255)->nullable();
            $table->string('no_hp_peserta', 20)->nullable();
            $table->string('status_registrasi', 1)->default('P')->comment('P=Pending, A=Approved, R=Rejected');
            $table->text('catatan_registrasi')->nullable();
            $table->string('created_by_registrasi', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_event_registrasi');
    }
};
