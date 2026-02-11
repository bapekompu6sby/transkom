<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('requester_position')->nullable()->after('requester_name');
            $table->string('organization_name')->nullable()->after('requester_position');
            $table->text('purpose')->nullable()->after('destination');
            $table->unsignedInteger('participant_count')->nullable()->after('purpose');
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn([
                'requester_position',
                'organization_name',
                'purpose',
                'participant_count',
            ]);
        });
    }
};
