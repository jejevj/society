<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_event_registrasi', function (Blueprint $table) {
            // Simpan snap token Midtrans agar tidak perlu generate ulang
            $table->text('snap_token')->nullable()->after('midtrans_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('t_event_registrasi', function (Blueprint $table) {
            $table->dropColumn('snap_token');
        });
    }
};
