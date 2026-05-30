<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_event_program_detail', function (Blueprint $table) {
            $table->increments('id_event_program_detail');
            $table->string('kode_event_program_detail', 50)->nullable();
            $table->string('event_program_kode', 30)->nullable();
            $table->string('event_kode', 20)->nullable();
            $table->string('awal_program_detail', 20)->nullable();
            $table->string('akhir_program_detail', 20)->nullable();
            $table->string('sesi_program_detail')->nullable();
            $table->text('keterangan_program_detail')->nullable();
            $table->timestamp('created_at_program_detail')->nullable()->useCurrentOnUpdate();
            $table->dateTime('updated_at_program_detail')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_event_program_detail');
    }
};
