<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reff_topik', function (Blueprint $table) {
            $table->bigIncrements('id_topik');
            $table->string('kode_topik', 20)->nullable();
            $table->string('nama_topik')->nullable();
            $table->integer('urutan_topik')->nullable();
            $table->text('deskripsi_topik')->nullable();
            $table->tinyInteger('status_topik')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reff_topik');
    }
};
