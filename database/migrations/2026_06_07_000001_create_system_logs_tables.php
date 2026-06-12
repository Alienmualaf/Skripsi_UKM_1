<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('username');
            $table->string('role');
            $table->text('activity');
            $table->string('module');
            $table->string('ip_address')->nullable();
            $table->string('browser')->nullable();
            $table->timestamps();
        });

        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('username');
            $table->timestamp('login_at')->nullable();
            $table->timestamp('logout_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('status')->default('Success');
            $table->timestamps();
        });

        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('username');
            $table->string('model_name');
            $table->string('action'); // create, update, delete
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
        Schema::dropIfExists('login_histories');
        Schema::dropIfExists('activity_logs');
    }
};
