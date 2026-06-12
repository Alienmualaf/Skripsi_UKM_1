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
        // 1. Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // administrator, admin_ukm, pengurus, anggota
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 2. Permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Role-Permission Pivot
        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });

        // 3. Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->constrained('roles')->onDelete('restrict');
            $table->string('status')->default('active'); // active, inactive
            $table->rememberToken();
            $table->timestamps();
        });

        // 4. Voice Classifications
        Schema::create('voice_classifications', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sopran, Alto, Tenor, Bass
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 5. Members
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('npm')->unique();
            $table->string('name');
            $table->string('gender'); // L, P
            $table->string('faculty');
            $table->string('major');
            $table->string('class_year');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->text('address');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('photo')->nullable();
            $table->string('status')->default('Calon Anggota'); // Calon Anggota, Anggota Aktif, Alumni, Nonaktif
            $table->foreignId('voice_classification_id')->nullable()->constrained('voice_classifications')->onDelete('set null');
            $table->timestamps();
        });

        // 6. Trainers
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('specialty'); // Bidang
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->decimal('salary', 15, 2)->default(0); // Honor Pelatih
            $table->string('status')->default('Aktif'); // Aktif, Nonaktif
            $table->timestamps();
        });

        // 7. Registrations
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('npm')->unique();
            $table->string('gender'); // L, P
            $table->string('faculty');
            $table->string('major');
            $table->string('class_year');
            $table->string('phone');
            $table->string('email');
            $table->text('choir_experience')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('Pending'); // Pending, Verifikasi, Terima, Tolak
            $table->timestamps();
        });

        // 8. Agendas
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->text('description')->nullable();
            $table->string('pic'); // Penanggung Jawab
            $table->string('status')->default('Akan Datang'); // Akan Datang, Berlangsung, Selesai
            $table->timestamps();
        });

        // 9. Programs
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('division');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget', 15, 2)->default(0);
            $table->integer('progress')->default(0); // 0-100%
            $table->string('status')->default('Perencanaan'); // Perencanaan, Berjalan, Selesai
            $table->timestamps();
        });

        // 10. Jobs
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('institution');
            $table->string('location');
            $table->date('date');
            $table->time('time');
            $table->decimal('fee', 15, 2)->default(0);
            $table->string('pic');
            $table->text('rundown')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 11. Job Members Pivot
        Schema::create('job_members', function (Blueprint $table) {
            $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->primary(['job_id', 'member_id']);
        });

        // 12. Attendances
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // Latihan, Rapat, Penampilan
            $table->date('date');
            $table->string('qr_code')->nullable();
            $table->boolean('is_active_qr')->default(false);
            $table->timestamps();
        });

        // 13. Attendance Details
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendances')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('status')->default('Alpha'); // Hadir, Izin, Sakit, Alpha
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 14. Inventories
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('category'); // Kostum, Alat Musik, Sound System, Perlengkapan Latihan, Perlengkapan Acara
            $table->string('condition'); // Baik, Rusak, Hilang
            $table->integer('quantity');
            $table->string('storage_location');
            $table->timestamps();
        });

        // 15. Inventory Loans
        Schema::create('inventory_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->date('loan_date');
            $table->date('return_date');
            $table->date('actual_return_date')->nullable();
            $table->integer('quantity');
            $table->string('condition_on_loan');
            $table->string('condition_on_return')->nullable();
            $table->string('status')->default('Dipinjam'); // Dipinjam, Dikembalikan
            $table->timestamps();
        });

        // 16. Finance Categories
        Schema::create('finance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // income, expense
            $table->timestamps();
        });

        // 17. Finances
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_category_id')->constrained('finance_categories')->onDelete('restrict');
            $table->string('type'); // income, expense
            $table->decimal('amount', 15, 2);
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->string('receipt_file')->nullable();
            $table->timestamps();
        });

        // 18. Letters
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number');
            $table->date('date');
            $table->string('subject');
            $table->string('destination');
            $table->string('type'); // Surat Tugas, Surat Permohonan, Surat Undangan, Surat Peminjaman, Surat Keterangan
            $table->string('file_path');
            $table->timestamps();
        });

        // 19. Folders
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('folders')->onDelete('cascade');
            $table->timestamps();
        });

        // 20. Materials
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->nullable()->constrained('folders')->onDelete('cascade');
            $table->string('title');
            $table->string('type'); // Partitur, Audio, Video
            $table->string('file_path');
            $table->string('file_type'); // pdf, docx, mp3, wav, mp4, mov
            $table->text('description')->nullable();
            $table->foreignId('uploader_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 21. Announcements
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 22. Galleries
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path');
            $table->text('description')->nullable();
            $table->string('type')->default('photo'); // photo, video
            $table->timestamps();
        });

        // 23. Achievements
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        // Cache, Sessions, etc. are created by default Laravel skeletons, let's keep them if needed but we have standard migrations.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('folders');
        Schema::dropIfExists('letters');
        Schema::dropIfExists('finances');
        Schema::dropIfExists('finance_categories');
        Schema::dropIfExists('inventory_loans');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('attendance_details');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('job_members');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('agendas');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('trainers');
        Schema::dropIfExists('members');
        Schema::dropIfExists('voice_classifications');
        Schema::dropIfExists('users');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
