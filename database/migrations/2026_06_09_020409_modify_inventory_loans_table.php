<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_loans', function (Blueprint $table) {
            $table->foreignId('member_id')->nullable()->change();
            $table->string('borrower_name')->nullable();
            $table->string('loan_letter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_loans', function (Blueprint $table) {
            $table->foreignId('member_id')->nullable(false)->change();
            $table->dropColumn(['borrower_name', 'loan_letter']);
        });
    }
};
