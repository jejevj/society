<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_user', function (Blueprint $table) {
            if (!Schema::hasColumn('app_user', 'tipe_organisasi_user')) {
                $table->string('tipe_organisasi_user', 100)->nullable()->after('organisasi_user');
            }
            if (!Schema::hasColumn('app_user', 'jabatan_user')) {
                $table->string('jabatan_user', 150)->nullable()->after('tipe_organisasi_user');
            }
        });
    }

    public function down(): void
    {
        Schema::table('app_user', function (Blueprint $table) {
            $table->dropColumnIfExists('tipe_organisasi_user');
            $table->dropColumnIfExists('jabatan_user');
        });
    }
};
