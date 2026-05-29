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
        
        Schema::create('app_log_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('id_log'); 
            $table->string('ip_log', 255)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('user_log', 255)->nullable();
            $table->string('fungsi_log', 255)->nullable();
            $table->text('deskripsi_log')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_log_aktivitas');
    }
};
