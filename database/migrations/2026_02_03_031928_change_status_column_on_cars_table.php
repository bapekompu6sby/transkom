<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Normalisasi data lama
        DB::table('cars')
            ->whereIn('status', ['rented', 'maintenance'])
            ->update(['status' => 'notavailable']);

        // 2. Ubah struktur kolom jadi string (kalau sebelumnya enum)
        Schema::table('cars', function (Blueprint $table) {
            $table->string('status', 20)->default('available')->change();
        });
    }

    public function down(): void
    {
        // Rollback struktur
        Schema::table('cars', function (Blueprint $table) {
            $table->string('status', 20)->default('available')->change();
        });

        // Rollback data (opsional, best-effort)
        DB::table('cars')
            ->where('status', 'notavailable')
            ->update(['status' => 'maintenance']);
    }
};
