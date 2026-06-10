<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_peserta_invite', function (Blueprint $table) {
            $table->id();
            $table->string('token', 100)->unique()->index();
            $table->string('kode_cart', 50)->nullable();
            $table->string('kode_event', 50);
            $table->string('kode_registrasi', 50)->nullable();
            $table->string('nama_peserta', 255);
            $table->string('email_peserta', 255);
            $table->string('no_hp_peserta', 50)->nullable();
            $table->string('instansi_peserta', 255)->nullable();
            $table->string('nama_event', 255);
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->enum('status', ['PENDING', 'USED', 'EXPIRED'])->default('PENDING');
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_peserta_invite');
    }
};
