<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_event_program', function (Blueprint $table) {
            $table->increments('id_event_program');
            $table->string('kode_event_program', 30)->nullable();
            $table->string('event_kode_program', 20)->nullable();
            $table->string('hari_program', 100)->nullable();
            $table->date('tanggal_program')->nullable();
            $table->timestamp('created_at_program')->nullable()->useCurrentOnUpdate();
            $table->dateTime('updated_at_program')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_event_program');
    }
};
