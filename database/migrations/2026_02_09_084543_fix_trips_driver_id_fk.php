<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            // drop FK lama yang ke users
            $table->dropForeign('trips_driver_id_foreign');

            // pastikan nullable
            $table->foreignId('driver_id')->nullable()->change();

            // buat FK baru ke drivers
            $table->foreign('driver_id')
                ->references('id')
                ->on('drivers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);

            $table->foreign('driver_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }
};
