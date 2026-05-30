<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_paper', function (Blueprint $table) {
            $table->increments('id_paper');
            $table->string('kode_paper', 30)->unique();
            $table->string('kode_registrasi', 30)->nullable();
            $table->string('kode_event', 20)->nullable();
            $table->string('judul_paper', 255)->nullable();
            $table->text('deskripsi_paper')->nullable();
            $table->string('file_paper', 255)->nullable();
            $table->string('tipe_file_paper', 10)->nullable()->comment('pdf, ppt, pptx');
            $table->string('status_paper', 1)->default('P')->comment('P=Pending, A=Approved, R=Rejected');
            $table->text('catatan_paper')->nullable();
            $table->string('created_by_paper', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_paper');
    }
};
