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
        // 1. Finances
        Schema::table('finances', function (Blueprint $table) {
            $table->string('used_for')->default('Umum')->after('type'); // Umum, Program Kerja
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null')->after('used_for');
        });

        // 2. Inventories
        Schema::table('inventories', function (Blueprint $table) {
            $table->string('used_for')->default('Umum')->after('condition'); // Umum, Program Kerja
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null')->after('used_for');
        });

        // 3. Inventory Loans
        Schema::table('inventory_loans', function (Blueprint $table) {
            $table->string('used_for')->default('Umum')->after('status'); // Umum, Program Kerja
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null')->after('used_for');
        });

        // 4. Letters
        Schema::table('letters', function (Blueprint $table) {
            $table->string('related_to')->default('Umum')->after('type'); // Umum, Program Kerja
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null')->after('related_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropColumn(['related_to', 'program_id']);
        });

        Schema::table('inventory_loans', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropColumn(['used_for', 'program_id']);
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropColumn(['used_for', 'program_id']);
        });

        Schema::table('finances', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropColumn(['used_for', 'program_id']);
        });
    }
};
