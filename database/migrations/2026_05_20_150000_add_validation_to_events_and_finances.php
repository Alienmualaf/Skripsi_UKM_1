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
        Schema::table('events', function (Blueprint $table) {
            $table->string('validation_status')->default('pending'); // pending, approved, rejected, revision
            $table->text('validation_notes')->nullable();
        });

        Schema::table('finances', function (Blueprint $table) {
            $table->string('validation_status')->default('pending'); // pending, approved, rejected, revision
            $table->text('validation_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['validation_status', 'validation_notes']);
        });

        Schema::table('finances', function (Blueprint $table) {
            $table->dropColumn(['validation_status', 'validation_notes']);
        });
    }
};
