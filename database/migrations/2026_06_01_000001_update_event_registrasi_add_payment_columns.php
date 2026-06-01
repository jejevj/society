<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_event_registrasi', function (Blueprint $table) {
            // Payment & flow status
            if (!Schema::hasColumn('t_event_registrasi', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('status_registrasi');
            }
            if (!Schema::hasColumn('t_event_registrasi', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('confirmed_at');
            }
        });

        // Ensure t_event_addon has the right columns
        Schema::table('t_event_addon', function (Blueprint $table) {
            if (!Schema::hasColumn('t_event_addon', 'id_user')) {
                $table->bigInteger('id_user')->nullable()->after('id')->unsigned();
            }
            if (!Schema::hasColumn('t_event_addon', 'kode_event')) {
                $table->string('kode_event', 50)->nullable()->after('id_user');
            }
            if (!Schema::hasColumn('t_event_addon', 'kode_paket')) {
                $table->string('kode_paket', 50)->nullable()->after('kode_event');
            }
            if (!Schema::hasColumn('t_event_addon', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
        });

        // Ensure app_midtrans_transaction has kode_event column
        if (Schema::hasTable('app_midtrans_transaction')) {
            Schema::table('app_midtrans_transaction', function (Blueprint $table) {
                if (!Schema::hasColumn('app_midtrans_transaction', 'kode_event')) {
                    $table->string('kode_event', 50)->nullable()->after('gross_amount');
                }
                if (!Schema::hasColumn('app_midtrans_transaction', 'user_id')) {
                    $table->bigInteger('user_id')->nullable()->after('kode_event');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('t_event_registrasi', function (Blueprint $table) {
            $table->dropColumnIfExists('confirmed_at');
        });
    }
};
