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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'division']);
        });

        Schema::table('trainers', function (Blueprint $table) {
            $table->dropColumn(['email', 'salary', 'status']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['qr_code', 'is_active_qr']);
        });

        Schema::table('performances', function (Blueprint $table) {
            $table->dropColumn(['institution', 'fee', 'pic', 'rundown', 'dress_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->string('division')->nullable()->after('status');
        });

        Schema::table('trainers', function (Blueprint $table) {
            $table->string('email')->nullable()->after('phone');
            $table->decimal('salary', 15, 2)->default(0)->after('photo');
            $table->string('status')->default('Aktif')->after('salary');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->string('qr_code')->nullable()->after('date');
            $table->boolean('is_active_qr')->default(false)->after('qr_code');
        });

        Schema::table('performances', function (Blueprint $table) {
            $table->string('institution')->nullable()->after('status');
            $table->decimal('fee', 15, 2)->default(0)->after('institution');
            $table->string('pic')->nullable()->after('fee');
            $table->text('rundown')->nullable()->after('pic');
            $table->string('dress_code')->nullable()->after('rundown');
        });
    }
};
