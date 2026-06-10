<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_event_registrasi', function (Blueprint $table) {
            if (!Schema::hasColumn('t_event_registrasi', 'kode_cart')) {
                $table->string('kode_cart', 50)->nullable()->after('kode_event');
            }
        });
    }

    public function down(): void
    {
        Schema::table('t_event_registrasi', function (Blueprint $table) {
            if (Schema::hasColumn('t_event_registrasi', 'kode_cart')) {
                $table->dropColumn('kode_cart');
            }
        });
    }
};
