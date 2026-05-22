<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('coach_attendances', 'session_id')) {
            Schema::table('coach_attendances', function (Blueprint $table) {
                $table->foreignId('session_id')->nullable()->after('event_id')->constrained('lms_sessions')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
