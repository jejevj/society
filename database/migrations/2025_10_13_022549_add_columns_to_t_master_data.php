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
        Schema::table('t_master_data', function (Blueprint $table) {
            $table->string('prioritas_master',10)->default('N');
            $table->string('metodologi_master',255)->nullable();
            $table->string('jenis_master',255)->nullable();
            $table->string('eselon1_master',255)->nullable();
            $table->string('eselon2_master',255)->nullable();
            $table->string('penanggung_jawab_master',255)->nullable();
            $table->text('cakupan_wilayah_master')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_master_data', function (Blueprint $table) {
            //
        });
    }
};
