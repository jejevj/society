<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_event_cart_peserta', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cart', 50)->index();
            $table->unsignedInteger('urutan')->default(1);
            $table->string('nama_peserta', 255);
            $table->string('email_peserta', 255);
            $table->string('no_hp_peserta', 50);
            $table->string('instansi_peserta', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_event_cart_peserta');
    }
};
