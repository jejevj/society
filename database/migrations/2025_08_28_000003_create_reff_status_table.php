<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reff_status', function (Blueprint $table) {
            $table->bigIncrements('id_status');
            $table->string('kode_status')->nullable();
            $table->string('keterangan_status')->nullable();
            $table->text('deskripsi_status')->nullable();
            $table->string('jenis_status')->nullable();
            $table->integer('urutan_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reff_status');
    }
};
