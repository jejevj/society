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
        Schema::table('t_event', function (Blueprint $table) {
            $table->float('harga_event', 10, 0)
                  ->nullable()
                  ->after('sub_judul_event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_event', function (Blueprint $table) {
            $table->dropColumn('harga_event');
        });
    }
};