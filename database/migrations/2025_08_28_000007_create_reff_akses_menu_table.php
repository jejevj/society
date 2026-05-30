<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reff_akses_menu', function (Blueprint $table) {
            $table->bigIncrements('id_akses_menu');
            $table->integer('role_id')->nullable();
            $table->integer('menu_id')->nullable();
            $table->tinyInteger('permit_r')->default(1);
            $table->tinyInteger('permit_c')->default(1);
            $table->tinyInteger('permit_u')->default(1);
            $table->tinyInteger('permit_d')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reff_akses_menu');
    }
};
