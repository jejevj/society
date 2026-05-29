<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ubah kolom jadi integer biasa
        DB::statement('ALTER TABLE reff_organisasi ALTER COLUMN id_organisasi TYPE integer;');
    }

    public function down(): void
    {
        // rollback ke bigint
        DB::statement('ALTER TABLE reff_organisasi ALTER COLUMN id_organisasi TYPE bigint;');
    }
};
