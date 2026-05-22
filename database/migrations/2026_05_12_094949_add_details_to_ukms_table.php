<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ukms', function (Blueprint $table) {
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->string('structure_image')->nullable();
            $table->boolean('is_recruitment_open')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('ukms', function (Blueprint $table) {
            $table->dropColumn(['vision', 'mission', 'structure_image', 'is_recruitment_open']);
        });
    }
};
