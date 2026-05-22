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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ukm_id');
            $table->unsignedBigInteger('created_by');
            $table->string('name');
            $table->integer('quantity');
            $table->string('condition');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('ukm_id')->references('id')->on('ukms')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
