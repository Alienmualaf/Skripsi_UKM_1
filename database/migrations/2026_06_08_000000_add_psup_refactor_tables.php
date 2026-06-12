<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add activity_type to programs
        Schema::table('programs', function (Blueprint $table) {
            $table->string('activity_type')->default('Internal')->after('status');
            // Internal, Event, Competition, Performance
            $table->string('target_date')->nullable()->after('activity_type');
            $table->string('pic')->nullable()->after('target_date');
        });

        // 2. Add division to users (for pengurus dynamic sidebar)
        Schema::table('users', function (Blueprint $table) {
            $table->string('division')->nullable()->after('status');
            // Sekretaris, Bendahara, Divisi Latihan, Divisi Humas, Divisi Perlengkapan
        });

        // 3. Performances (sub-data of Program Kerja type=Performance)
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->string('title');
            $table->string('venue');
            $table->date('performance_date');
            $table->time('performance_time')->nullable();
            $table->text('description')->nullable();
            $table->string('dress_code')->nullable();
            $table->string('status')->default('Persiapan'); // Persiapan, Berlangsung, Selesai
            $table->timestamps();
        });

        // 4. Classrooms (per Performance)
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performance_id')->constrained('performances')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('Aktif'); // Aktif, Selesai
            $table->timestamps();
        });

        // 5. Classroom Members pivot
        Schema::create('classroom_members', function (Blueprint $table) {
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('role')->default('Peserta'); // Peserta, PJ
            $table->primary(['classroom_id', 'member_id']);
        });

        // 6. Add classroom_id to Announcements, Attendances, and Materials to merge duplicate tables
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('cascade')->after('id');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('cascade')->after('id');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('cascade')->after('folder_id');
        });

        // 10. Classroom Schedules (Jadwal Latihan)
        Schema::create('classroom_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            $table->string('title');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 11. Classroom Song Targets
        Schema::create('classroom_song_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            $table->string('song_title');
            $table->string('composer')->nullable();
            $table->string('voice_part')->nullable(); // Sopran, Alto, Tenor, Bass, Full Choir
            $table->string('status')->default('Belajar'); // Belajar, Hafal, Siap Tampil
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 12. Program Reports (LPJ per Program Kerja)
        Schema::create('program_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('executive_summary')->nullable();
            $table->text('activities_description')->nullable();
            $table->text('budget_realization')->nullable();
            $table->text('obstacles')->nullable();
            $table->text('recommendations')->nullable();
            $table->decimal('realized_budget', 15, 2)->default(0);
            $table->string('status')->default('Draft'); // Draft, Submitted, Approved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn('classroom_id');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn('classroom_id');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn('classroom_id');
        });

        Schema::dropIfExists('program_reports');
        Schema::dropIfExists('classroom_song_targets');
        Schema::dropIfExists('classroom_schedules');
        Schema::dropIfExists('classroom_members');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('performances');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('division');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['activity_type', 'target_date', 'pic']);
        });
    }
};
