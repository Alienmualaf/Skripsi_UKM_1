<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->string('voice_classification')->nullable()->comment('Sopran, Alto, Tenor, Bass, dll');
        });
    }

    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('voice_classification');
        });
    }
};
