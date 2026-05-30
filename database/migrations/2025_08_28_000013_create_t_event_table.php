<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_event', function (Blueprint $table) {
            $table->increments('id_event');
            $table->string('kode_event', 20)->nullable();
            $table->string('judul_event')->nullable();
            $table->string('sub_judul_event')->nullable();
            $table->text('keterangan_event')->nullable();
            $table->string('lokasi_event')->nullable();
            $table->date('tanggal_awal_event')->nullable();
            $table->date('tanggal_akhir_event')->nullable();
            $table->string('status_event', 1)->default('Y');
            $table->string('background_event')->nullable();
            $table->string('created_by_event')->nullable();
            $table->timestamp('created_at_event')->nullable()->useCurrentOnUpdate();
            $table->dateTime('updated_at_event')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_event');
    }
};
