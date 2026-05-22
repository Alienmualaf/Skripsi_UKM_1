<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // 1. Drop foreign keys first because they depend on the unique index in some MySQL versions
            $table->dropForeign(['event_id']);
            $table->dropForeign(['user_id']);
            
            // 2. Drop the old unique constraint
            $table->dropUnique(['event_id', 'user_id']);
            
            // 3. Add new unique constraint per session
            $table->unique(['session_id', 'user_id']);
            
            // 4. Re-add foreign keys
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('coach_attendances', function (Blueprint $table) {
            // Ensure session_id and coach_id is unique
            $table->unique(['session_id', 'coach_id']);
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropForeign(['user_id']);
            
            $table->dropUnique(['session_id', 'user_id']);
            $table->unique(['event_id', 'user_id']);
            
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('coach_attendances', function (Blueprint $table) {
            $table->dropUnique(['session_id', 'coach_id']);
        });
    }
};
