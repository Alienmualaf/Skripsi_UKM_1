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
        // Add event_id to existing tables
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
        });

        Schema::table('finances', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
        });

        // Create pivot table for event participants
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('membership_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['event_id', 'membership_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participants');

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });

        Schema::table('finances', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};
