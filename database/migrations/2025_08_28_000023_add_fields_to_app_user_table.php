<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_user', function (Blueprint $table) {
            $table->string('nationality_user', 100)->nullable()->after('organisasi_user');
            $table->string('job_title_user', 150)->nullable()->after('nationality_user');
        });
    }

    public function down(): void
    {
        Schema::table('app_user', function (Blueprint $table) {
            $table->dropColumn(['nationality_user', 'job_title_user']);
        });
    }
};
