<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_addon', function (Blueprint $table) {
            $table->id();
            $table->string('kode_addon', 50)->unique();
            $table->string('kode_event', 50)->index();
            $table->string('nama_addon', 255);
            $table->text('deskripsi_addon')->nullable();
            $table->string('gambar_addon', 255)->nullable();
            $table->decimal('harga_addon', 15, 2)->default(0);
            $table->enum('status_addon', ['A', 'N'])->default('A')->comment('A=Active, N=Inactive');
            $table->timestamps();
        });

        Schema::create('event_addon_registrasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_addon_reg', 50)->unique();
            $table->string('kode_registrasi', 50)->index();
            $table->string('kode_addon', 50)->index();
            $table->enum('status', ['P', 'A', 'R'])->default('P')->comment('P=Pending, A=Approved, R=Rejected');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_addon_registrasi');
        Schema::dropIfExists('event_addon');
    }
};
