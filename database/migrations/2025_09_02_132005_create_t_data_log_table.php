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
        Schema::create('t_data_log', function (Blueprint $table) {
            $table->bigIncrements('id_data_log'); 
            $table->string('data_kode_log',255)->nullable();
            $table->integer('data_id_log')->nullable();
            $table->string('user_id_log',255)->nullable();
            $table->string('user_nama_log',255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_data_log');
    }
};
