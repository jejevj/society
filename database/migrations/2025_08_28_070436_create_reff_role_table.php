<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reff_role', function (Blueprint $table) {
            $table->bigIncrements('id_role'); 
            $table->string('nama_role', 100)->nullable();
            $table->string('kode_role', 100)->nullable();
            $table->text('deskripsi_role')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reff_role');
    }
};
