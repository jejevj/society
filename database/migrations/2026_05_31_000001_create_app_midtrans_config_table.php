<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_midtrans_config', function (Blueprint $table) {
            $table->id('id_midtrans');
            $table->string('server_key', 255)->nullable();
            $table->string('client_key', 255)->nullable();
            $table->enum('environment', ['sandbox', 'production'])->default('sandbox');
            $table->json('payment_types')->nullable();
            $table->string('merchant_id', 100)->nullable();
            $table->string('webhook_url', 500)->nullable();
            $table->string('finish_redirect_url', 500)->nullable();
            $table->string('unfinish_redirect_url', 500)->nullable();
            $table->string('error_redirect_url', 500)->nullable();
            $table->enum('is_active', ['Y', 'N'])->default('N');
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_midtrans_config');
    }
};
