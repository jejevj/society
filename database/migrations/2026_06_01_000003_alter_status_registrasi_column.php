<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alter status_registrasi from varchar(1) to varchar(30)
        // Old: 'P', 'A', 'R'
        // New: 'PENDING_OTP', 'PENDING_PAYMENT', 'CONFIRMED', 'REJECTED', etc.
        DB::statement("ALTER TABLE t_event_registrasi MODIFY COLUMN status_registrasi VARCHAR(30) NOT NULL DEFAULT 'P' COMMENT 'P=Pending, A=Approved, R=Rejected, PENDING_OTP, PENDING_PAYMENT, CONFIRMED'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE t_event_registrasi MODIFY COLUMN status_registrasi VARCHAR(1) NOT NULL DEFAULT 'P' COMMENT 'P=Pending, A=Approved, R=Rejected'");
    }
};
