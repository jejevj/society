<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('app_midtrans_transaction')) {
            Schema::create('app_midtrans_transaction', function (Blueprint $table) {
                $table->id('id_transaksi');
                $table->string('order_id', 100)->unique();
                $table->string('transaction_id', 100)->nullable();
                $table->string('transaction_status', 50)->nullable();
                $table->string('payment_type', 100)->nullable();
                $table->decimal('gross_amount', 15, 2)->nullable();
                $table->string('currency', 10)->default('IDR');
                $table->string('fraud_status', 50)->nullable();
                $table->string('status_message', 255)->nullable();
                $table->string('bank', 100)->nullable();
                $table->string('masked_card', 50)->nullable();
                $table->string('approval_code', 100)->nullable();
                $table->text('snap_token')->nullable();
                $table->text('redirect_url')->nullable();
                $table->longText('raw_response')->nullable();
                $table->timestamp('transaction_time')->nullable();
                $table->timestamp('settlement_time')->nullable();
                $table->string('created_by', 100)->nullable();
                $table->string('updated_by', 100)->nullable();
                $table->timestamps();
            });
        } else {
            // Pastikan kolom snap_token & redirect_url ada (untuk DB yang dibuat sebelum kolom ini ditambahkan)
            Schema::table('app_midtrans_transaction', function (Blueprint $table) {
                if (!Schema::hasColumn('app_midtrans_transaction', 'snap_token')) {
                    $table->text('snap_token')->nullable()->after('approval_code');
                }
                if (!Schema::hasColumn('app_midtrans_transaction', 'redirect_url')) {
                    $table->text('redirect_url')->nullable()->after('snap_token');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('app_midtrans_transaction');
    }
};
