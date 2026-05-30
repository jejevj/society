<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_midtrans_transaction', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('order_id', 100)->unique();
            $table->string('transaction_id', 100)->nullable();
            $table->string('transaction_status', 50)->nullable(); // pending, settlement, cancel, expire, deny, refund
            $table->string('payment_type', 100)->nullable();
            $table->decimal('gross_amount', 15, 2)->nullable();
            $table->string('currency', 10)->default('IDR');
            $table->string('fraud_status', 50)->nullable();
            $table->string('status_message', 255)->nullable();
            $table->string('bank', 50)->nullable();
            $table->string('masked_card', 50)->nullable();
            $table->string('approval_code', 50)->nullable();
            $table->text('raw_response')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_midtrans_transaction');
    }
};
