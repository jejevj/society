<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_event_timeline', function (Blueprint $table) {
            $table->increments('id_timeline');
            $table->string('kode_timeline', 30)->unique();
            $table->string('kode_event', 20)->nullable();
            $table->unsignedTinyInteger('hari_ke')->nullable()->comment('Day number, e.g. 1, 2, 3');
            $table->date('tanggal_timeline')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('judul_sesi', 255)->nullable();
            $table->text('deskripsi_sesi')->nullable();
            $table->string('status_timeline', 1)->default('Y')->comment('Y=Active, N=Inactive');
            $table->string('created_by_timeline', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_event_timeline');
    }
};
