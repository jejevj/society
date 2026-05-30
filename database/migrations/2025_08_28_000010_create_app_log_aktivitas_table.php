<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_log_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('id_log');
            $table->string('ip_log')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('user_log')->nullable();
            $table->string('fungsi_log')->nullable();
            $table->text('deskripsi_log')->nullable();
            $table->text('data_lama_log')->nullable();
            $table->text('data_baru_log')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_log_aktivitas');
    }
};
