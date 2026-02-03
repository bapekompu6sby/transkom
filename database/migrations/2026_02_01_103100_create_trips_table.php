<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('car_id')
                ->constrained('cars')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->boolean('driver_required')->default(false);

            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('requester_name')->nullable();

            $table->string('destination');

            $table->string('status', 20)->default('pending');

            $table->text('notes')->nullable();
            $table->text('notes_cancel')->nullable();

            $table->dateTime('start_at');
            $table->dateTime('end_at');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
