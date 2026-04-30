<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_diagnosas', function (Blueprint $table) {
            $table->string('nama_pelanggan')->nullable()->after('status');
            $table->string('alamat_pelanggan')->nullable()->after('nama_pelanggan');
            $table->string('nomor_polisi')->nullable()->after('alamat_pelanggan');
            $table->string('nomor_telepon')->nullable()->after('nomor_polisi');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_diagnosas', function (Blueprint $table) {
            $table->dropColumn([
                'nama_pelanggan',
                'alamat_pelanggan',
                'nomor_polisi',
                'nomor_telepon',
            ]);
        });
    }
};
