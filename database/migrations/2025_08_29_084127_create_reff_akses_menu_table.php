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
        Schema::create('reff_akses_menu', function (Blueprint $table) {
            $table->bigIncrements('id_akses_menu'); 
            $table->integer('role_id')->nullable();
            $table->integer('menu_id')->nullable();
            $table->boolean('permit_r')->default(true);
            $table->boolean('permit_c')->default(true);
            $table->boolean('permit_u')->default(true);
            $table->boolean('permit_d')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reff_akses_menu');
    }
};
