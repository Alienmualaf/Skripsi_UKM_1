<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            // Make performance_id nullable
            $table->foreignId('performance_id')->nullable()->change();
            
            // Add job_id
            $table->foreignId('job_id')->nullable()->constrained('jobs')->onDelete('cascade')->after('performance_id');
        });
    }

    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
            $table->dropColumn('job_id');
            $table->foreignId('performance_id')->nullable(false)->change();
        });
    }
};
