<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_event_cart', function (Blueprint $table) {

            $table->bigIncrements('id_event_cart');
            $table->string('kode_cart', 50)->unique();
            $table->string('kode_event', 100);
            $table->bigInteger('id_user');
            $table->integer('qty')->default(1);
            $table->decimal('harga', 18, 2)->default(0);
            $table->decimal('subtotal', 18, 2)->default(0);
            $table->timestamps();

            $table->index('kode_event');
            $table->index('id_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_event_cart');
    }
};
