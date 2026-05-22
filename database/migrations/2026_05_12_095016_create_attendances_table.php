<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('present')->comment('present, absent, sick, excused');
            $table->timestamps();
            
            // 1 user only has 1 attendance record per event
            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
