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
        Schema::table('app_user', function (Blueprint $table) {
            $table->string('identitas_user', 100)->nullable();
            $table->string('file_identitas_user', 255)->nullable();
            $table->string('telepon_user', 100)->nullable();
            $table->string('pekerjaan_user', 255)->nullable();
            $table->text('alamat_user')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_user', function (Blueprint $table) {
            //
        });
    }
};
