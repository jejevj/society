<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add base registration price to t_event
        Schema::table('t_event', function (Blueprint $table) {
            $table->decimal('harga_event', 15, 2)->default(0)->after('status_event');
            $table->string('mata_uang_event', 5)->default('IDR')->after('harga_event');
        });

        // Add price and addon flag to t_event_paket
        Schema::table('t_event_paket', function (Blueprint $table) {
            $table->decimal('harga_paket', 15, 2)->default(0)->after('keterangan_paket');
            $table->tinyInteger('is_addon')->default(0)->comment('0=included/free, 1=optional addon with price')->after('harga_paket');
            $table->integer('urutan_paket')->default(0)->after('is_addon');
        });
    }

    public function down(): void
    {
        Schema::table('t_event', function (Blueprint $table) {
            $table->dropColumn(['harga_event', 'mata_uang_event']);
        });
        Schema::table('t_event_paket', function (Blueprint $table) {
            $table->dropColumn(['harga_paket', 'is_addon', 'urutan_paket']);
        });
    }
};
