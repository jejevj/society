<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_event_cart_paket', function (Blueprint $table) {

            $table->bigIncrements('id_event_cart_paket');
            $table->string('kode_cart', 50);
            $table->string('kode_event_paket', 100);
            $table->string('event_kode', 100);
            $table->string('judul_paket', 255)->nullable();
            $table->decimal('harga_paket', 18, 2)->default(0);
            $table->timestamps();

            $table->index('kode_cart');
            $table->index('kode_event_paket');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_event_cart_paket');
    }
};
