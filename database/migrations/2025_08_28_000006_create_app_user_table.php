<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_user', function (Blueprint $table) {
            $table->bigIncrements('id_user');
            $table->integer('role_id')->nullable();
            $table->string('nama_user')->nullable();
            $table->string('username_user')->nullable();
            $table->string('password_user')->nullable();
            $table->string('foto_user')->nullable();
            $table->tinyInteger('status_user')->default(1);
            $table->string('identitas_user', 100)->nullable();
            $table->string('file_identitas_user')->nullable();
            $table->string('telepon_user', 100)->nullable();
            $table->string('pekerjaan_user')->nullable();
            $table->text('alamat_user')->nullable();
            $table->string('organisasi_user')->nullable();
            $table->string('verify_token')->nullable();
            $table->string('otp_user')->nullable();
            $table->string('is_otp', 1)->default('Y');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_user');
    }
};
