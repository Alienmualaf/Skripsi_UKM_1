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
        $foreignKeys = Schema::getForeignKeys('coach_attendances');
        $foreignKeyNames = array_map(fn($fk) => $fk['name'], $foreignKeys);
        
        $indexes = Schema::getIndexes('coach_attendances');
        $indexNames = array_map(fn($idx) => $idx['name'], $indexes);

        Schema::table('coach_attendances', function (Blueprint $table) use ($foreignKeyNames, $indexNames) {
            // Drop foreign keys if they exist
            if (in_array('coach_attendances_session_id_foreign', $foreignKeyNames)) {
                $table->dropForeign('coach_attendances_session_id_foreign');
            }
            if (in_array('coach_attendances_coach_id_foreign', $foreignKeyNames)) {
                $table->dropForeign('coach_attendances_coach_id_foreign');
            }
            
            // Drop unique index if it exists
            if (in_array('coach_attendances_session_id_coach_id_unique', $indexNames)) {
                $table->dropUnique('coach_attendances_session_id_coach_id_unique');
            }
        });

        Schema::table('coach_attendances', function (Blueprint $table) {
            // Make coach_id nullable
            $table->unsignedBigInteger('coach_id')->nullable()->change();

            // Add new columns
            if (!Schema::hasColumn('coach_attendances', 'category')) {
                $table->enum('category', ['pelatih', 'bph', 'lainnya'])->default('pelatih')->after('coach_id');
            }
            if (!Schema::hasColumn('coach_attendances', 'name')) {
                $table->string('name')->nullable()->after('category');
            }
            if (!Schema::hasColumn('coach_attendances', 'notes')) {
                $table->text('notes')->nullable()->after('name');
            }

            // Re-add foreign keys
            $table->foreign('coach_id')->references('id')->on('coaches')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('lms_sessions')->onDelete('cascade');
            
            // Re-add unique key for session_id + coach_id
            $table->unique(['session_id', 'coach_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $foreignKeys = Schema::getForeignKeys('coach_attendances');
        $foreignKeyNames = array_map(fn($fk) => $fk['name'], $foreignKeys);
        
        $indexes = Schema::getIndexes('coach_attendances');
        $indexNames = array_map(fn($idx) => $idx['name'], $indexes);

        Schema::table('coach_attendances', function (Blueprint $table) use ($foreignKeyNames, $indexNames) {
            if (in_array('coach_attendances_session_id_foreign', $foreignKeyNames)) {
                $table->dropForeign('coach_attendances_session_id_foreign');
            }
            if (in_array('coach_attendances_coach_id_foreign', $foreignKeyNames)) {
                $table->dropForeign('coach_attendances_coach_id_foreign');
            }
            if (in_array('coach_attendances_session_id_coach_id_unique', $indexNames)) {
                $table->dropUnique('coach_attendances_session_id_coach_id_unique');
            }
        });

        Schema::table('coach_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('coach_id')->nullable(false)->change();
            
            if (Schema::hasColumn('coach_attendances', 'category')) {
                $table->dropColumn(['category', 'name', 'notes']);
            }

            $table->foreign('coach_id')->references('id')->on('coaches')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('lms_sessions')->onDelete('cascade');
            $table->unique(['session_id', 'coach_id']);
        });
    }
};
