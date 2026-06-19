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
        Schema::table('finances', function (Blueprint $table) {
            $table->dropForeign(['finance_category_id']);
            $table->dropColumn('finance_category_id');
        });

        Schema::dropIfExists('finance_categories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('finance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // income, expense
            $table->timestamps();
        });

        Schema::table('finances', function (Blueprint $table) {
            $table->foreignId('finance_category_id')->nullable()->constrained('finance_categories')->onDelete('restrict');
        });
    }
};
