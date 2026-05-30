<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_event', function (Blueprint $table) {
            $table->enum('is_featured', ['Y', 'N'])->default('N')->after('status_event');
        });
    }

    public function down(): void
    {
        Schema::table('t_event', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
    }
};
