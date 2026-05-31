<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Jika tabel belum ada, buat dari awal
        if (!Schema::hasTable('app_midtrans_transaction')) {
            Schema::create('app_midtrans_transaction', function (Blueprint $table) {
                $table->id();
                $table->string('order_id')->unique();
                $table->string('transaction_id')->nullable();
                $table->string('transaction_status')->default('pending'); // pending, settlement, cancel, expire, deny, refund
                $table->string('payment_type')->nullable();
                $table->decimal('gross_amount', 15, 2)->default(0);
                $table->string('currency', 10)->default('IDR');
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('snap_token')->nullable();
                $table->string('redirect_url')->nullable();
                $table->string('fraud_status')->nullable();
                $table->string('bank')->nullable();
                $table->string('va_number')->nullable();
                $table->text('raw_response')->nullable();
                $table->timestamp('transaction_time')->nullable();
                $table->timestamp('settlement_time')->nullable();
                $table->timestamp('expiry_time')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            return;
        }

        // Tabel sudah ada — tambahkan kolom yang belum ada saja (safe alter)
        Schema::table('app_midtrans_transaction', function (Blueprint $table) {
            if (!Schema::hasColumn('app_midtrans_transaction', 'order_id')) {
                $table->string('order_id')->unique()->after('id');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('order_id');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'transaction_status')) {
                $table->string('transaction_status')->default('pending')->after('transaction_id');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('transaction_status');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'gross_amount')) {
                $table->decimal('gross_amount', 15, 2)->default(0)->after('payment_type');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'currency')) {
                $table->string('currency', 10)->default('IDR')->after('gross_amount');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'first_name')) {
                $table->string('first_name')->nullable()->after('currency');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'email')) {
                $table->string('email')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'redirect_url')) {
                $table->string('redirect_url')->nullable()->after('snap_token');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'fraud_status')) {
                $table->string('fraud_status')->nullable()->after('redirect_url');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'bank')) {
                $table->string('bank')->nullable()->after('fraud_status');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'va_number')) {
                $table->string('va_number')->nullable()->after('bank');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'raw_response')) {
                $table->text('raw_response')->nullable()->after('va_number');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'transaction_time')) {
                $table->timestamp('transaction_time')->nullable()->after('raw_response');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'settlement_time')) {
                $table->timestamp('settlement_time')->nullable()->after('transaction_time');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'expiry_time')) {
                $table->timestamp('expiry_time')->nullable()->after('settlement_time');
            }
            if (!Schema::hasColumn('app_midtrans_transaction', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_midtrans_transaction');
    }
};
