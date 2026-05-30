<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_event_kolaborasi', function (Blueprint $table) {
            $table->increments('id_event_kolaborasi');
            $table->string('kode_kolaborasi', 30)->nullable();
            $table->string('event_kode_kolaborasi', 20)->nullable();
            $table->string('nama_kolaborasi')->nullable();
            $table->string('gambar_kolaborasi')->nullable();
            $table->text('keterangan_kolaborasi')->nullable();
            $table->timestamp('created_at_kolaborasi')->nullable()->useCurrentOnUpdate();
            $table->dateTime('updated_at_kolaborasi')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_event_kolaborasi');
    }
};
