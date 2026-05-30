<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_event_paket_detail', function (Blueprint $table) {
            $table->increments('id_event_paket_detail');
            $table->string('kode_event_paket_detail', 50)->nullable();
            $table->string('event_paket_kode', 40)->nullable();
            $table->string('event_kode', 20)->nullable();
            $table->string('jenis_paket_detail', 50)->nullable();
            $table->string('nama_paket_detail')->nullable();
            $table->string('gambar_paket_detail')->nullable();
            $table->timestamp('created_at_paket_detail')->nullable()->useCurrentOnUpdate();
            $table->dateTime('updated_at_paket_detail')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_event_paket_detail');
    }
};
