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
        // Drop system tables
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('sessions');

        // Refactor Classification
        Schema::create('ukm_classifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ukm_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('voice_classification');
            $table->foreignId('ukm_classification_id')->nullable()->after('ukm_id')->constrained('ukm_classifications')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropForeign(['ukm_classification_id']);
            $table->dropColumn('ukm_classification_id');
            $table->string('voice_classification')->nullable();
        });
        Schema::dropIfExists('ukm_classifications');
    }
};
