<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_email', function (Blueprint $table) {
            $table->increments('id_app_email');
            $table->string('smtp_host')->nullable();
            $table->string('smtp_port')->nullable();
            $table->text('smtp_encryption')->nullable();
            $table->string('smtp_username')->nullable();
            $table->text('smtp_password')->nullable();
            $table->string('smtp_from_address')->nullable();
            $table->string('smtp_from_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_email');
    }
};
