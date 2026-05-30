<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reff_role', function (Blueprint $table) {
            $table->bigIncrements('id_role');
            $table->string('nama_role', 100)->nullable();
            $table->string('kode_role', 100)->nullable();
            $table->text('deskripsi_role')->nullable();
            $table->char('all_data_role', 1)->default('N');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reff_role');
    }
};
