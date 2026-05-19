<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemilik');
            $table->string('merk_motor');
            $table->string('plat_nomor');
            $table->text('keluhan');
            $table->foreignId('kerusakan_id')->nullable()->constrained('kerusakans')->onDelete('set null');
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->text('catatan_mekanik')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motors');
    }
};
