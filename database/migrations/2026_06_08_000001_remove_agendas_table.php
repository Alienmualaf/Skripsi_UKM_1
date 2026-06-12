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
        Schema::dropIfExists('agendas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->time('time');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('pic')->nullable();
            $table->string('status')->default('Akan Datang');
            $table->timestamps();
        });
    }
};
