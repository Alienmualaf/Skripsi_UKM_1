<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop foreign key constraints pointing to lms_sessions
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
        });

        Schema::table('coach_attendances', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
        });

        // 2. Rename the table
        Schema::rename('lms_sessions', 'activity_sessions');

        // 3. Re-create foreign key constraints pointing to activity_sessions
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign('session_id')
                  ->references('id')
                  ->on('activity_sessions')
                  ->onDelete('cascade');
        });

        Schema::table('coach_attendances', function (Blueprint $table) {
            $table->foreign('session_id')
                  ->references('id')
                  ->on('activity_sessions')
                  ->onDelete('cascade');
        });

        // 4. Update existing materials with type 'partitur' to 'dokumen'
        DB::table('materials')
            ->where('type', 'partitur')
            ->update(['type' => 'dokumen']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Drop foreign key constraints pointing to activity_sessions
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
        });

        Schema::table('coach_attendances', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
        });

        // 2. Rename the table back
        Schema::rename('activity_sessions', 'lms_sessions');

        // 3. Re-create foreign key constraints pointing to lms_sessions
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign('session_id')
                  ->references('id')
                  ->on('lms_sessions')
                  ->onDelete('cascade');
        });

        Schema::table('coach_attendances', function (Blueprint $table) {
            $table->foreign('session_id')
                  ->references('id')
                  ->on('lms_sessions')
                  ->onDelete('cascade');
        });

        // 4. Revert materials type 'dokumen' back to 'partitur' (optional but clean)
        DB::table('materials')
            ->where('type', 'dokumen')
            ->update(['type' => 'partitur']);
    }
};
