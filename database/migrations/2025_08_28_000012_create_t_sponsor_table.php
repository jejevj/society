<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_sponsor', function (Blueprint $table) {
            $table->increments('id_sponsor');
            $table->string('nama')->nullable();
            $table->string('logo')->nullable();
            $table->integer('urutan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_sponsor');
    }
};
