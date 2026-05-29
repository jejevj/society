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
        Schema::create('app_user', function (Blueprint $table) {
            $table->bigIncrements('id_user'); 
            $table->integer('role_id')->nullable();
            $table->integer('organisasi_id')->nullable();
            $table->string('nama_user', 255)->nullable();
            $table->string('username_user', 255)->nullable();
            $table->string('password_user', 255)->nullable();
            $table->string('foto_user', 255)->nullable();
            $table->boolean('status_user')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_user');
    }
};
