<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_diagnosas', function (Blueprint $table) {
            $table->softDeletes(); #add table deleted_at
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_diagnosas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};