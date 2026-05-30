<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_event_paket', function (Blueprint $table) {
            $table->increments('id_event_paket');
            $table->string('kode_paket', 30)->nullable();
            $table->string('event_kode_paket', 20)->nullable();
            $table->string('judul_paket')->nullable();
            $table->string('sub_judul_paket')->nullable();
            $table->text('keterangan_paket')->nullable();
            $table->string('gambar_paket')->nullable();
            $table->string('icon_paket')->nullable();
            $table->string('lokasi_paket')->nullable();
            $table->timestamp('created_at_paket')->nullable()->useCurrentOnUpdate();
            $table->dateTime('updated_at_paket')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_event_paket');
    }
};
