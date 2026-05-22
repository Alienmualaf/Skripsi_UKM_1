<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Handled by other fixes
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn('session_id');
            $table->dropUnique(['session_id', 'user_id']);
            $table->unique(['event_id', 'user_id']);
        });

        Schema::table('coach_attendances', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn('session_id');
        });
    }
};
