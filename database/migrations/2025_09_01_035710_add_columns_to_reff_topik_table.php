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
        Schema::table('reff_topik', function (Blueprint $table) {
            $table->text('deskripsi_topik')->nullable()->after('gambar_topik');
            $table->boolean('status_topik')->default(1)->after('deskripsi_topik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reff_topik', function (Blueprint $table) {
            $table->dropColumn(['deskripsi_topik', 'status_topik']);
        });
    }
};
