<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update t_event_registrasi — add payment & flow columns
        Schema::table('t_event_registrasi', function (Blueprint $table) {
            if (!Schema::hasColumn('t_event_registrasi', 'id_user')) {
                $table->unsignedBigInteger('id_user')->nullable()->after('kode_event');
            }
            if (!Schema::hasColumn('t_event_registrasi', 'role_peserta')) {
                $table->string('role_peserta', 100)->nullable()->after('id_user')->comment('Participant, Speaker, Sponsor, etc.');
            }
            if (!Schema::hasColumn('t_event_registrasi', 'kode_paket')) {
                $table->string('kode_paket', 50)->nullable()->after('role_peserta');
            }
            if (!Schema::hasColumn('t_event_registrasi', 'total_bayar')) {
                $table->decimal('total_bayar', 15, 2)->default(0)->after('kode_paket');
            }
            if (!Schema::hasColumn('t_event_registrasi', 'midtrans_order_id')) {
                $table->string('midtrans_order_id', 100)->nullable()->after('total_bayar');
            }
            if (!Schema::hasColumn('t_event_registrasi', 'payment_status')) {
                $table->string('payment_status', 30)->nullable()->default('UNPAID')->after('midtrans_order_id')->comment('UNPAID, PENDING, PAID, FAILED, EXPIRED');
            }
            if (!Schema::hasColumn('t_event_registrasi', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('t_event_registrasi', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('confirmed_at');
            }
        });

        // 2. Create t_event_addon if it doesn't exist
        if (!Schema::hasTable('t_event_addon')) {
            Schema::create('t_event_addon', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('id_user')->nullable();
                $table->string('kode_event', 50)->nullable();
                $table->string('kode_registrasi', 30)->nullable();
                $table->string('kode_paket', 50)->nullable();
                $table->string('nama_addon', 255)->nullable();
                $table->decimal('harga_addon', 15, 2)->default(0);
                $table->integer('qty')->default(1);
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        } else {
            // Table exists, just add missing columns
            Schema::table('t_event_addon', function (Blueprint $table) {
                if (!Schema::hasColumn('t_event_addon', 'id_user')) {
                    $table->unsignedBigInteger('id_user')->nullable()->after('id');
                }
                if (!Schema::hasColumn('t_event_addon', 'kode_event')) {
                    $table->string('kode_event', 50)->nullable()->after('id_user');
                }
                if (!Schema::hasColumn('t_event_addon', 'kode_registrasi')) {
                    $table->string('kode_registrasi', 30)->nullable()->after('kode_event');
                }
                if (!Schema::hasColumn('t_event_addon', 'kode_paket')) {
                    $table->string('kode_paket', 50)->nullable()->after('kode_registrasi');
                }
                if (!Schema::hasColumn('t_event_addon', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('t_event_addon', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        // 3. Ensure app_midtrans_transaction has needed columns
        if (Schema::hasTable('app_midtrans_transaction')) {
            Schema::table('app_midtrans_transaction', function (Blueprint $table) {
                if (!Schema::hasColumn('app_midtrans_transaction', 'kode_event')) {
                    $table->string('kode_event', 50)->nullable();
                }
                if (!Schema::hasColumn('app_midtrans_transaction', 'kode_registrasi')) {
                    $table->string('kode_registrasi', 30)->nullable();
                }
                if (!Schema::hasColumn('app_midtrans_transaction', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('t_event_registrasi', function (Blueprint $table) {
            $table->dropColumnIfExists('confirmed_at');
            $table->dropColumnIfExists('paid_at');
            $table->dropColumnIfExists('payment_status');
            $table->dropColumnIfExists('midtrans_order_id');
            $table->dropColumnIfExists('total_bayar');
            $table->dropColumnIfExists('kode_paket');
            $table->dropColumnIfExists('role_peserta');
            $table->dropColumnIfExists('id_user');
        });
        Schema::dropIfExists('t_event_addon');
    }
};
