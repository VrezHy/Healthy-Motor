<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_diagnosas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('kerusakan_id')->nullable()->constrained('kerusakans')->nullOnDelete();
            $table->string('nama_kerusakan');
            $table->unsignedTinyInteger('persentase')->default(0);
            $table->unsignedInteger('jumlah_gejala_cocok')->default(0);
            $table->unsignedInteger('total_gejala')->default(0);
            $table->json('gejala_terpilih')->nullable();
            $table->json('solusi')->nullable();
            $table->string('status')->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_diagnosas');
    }
};
