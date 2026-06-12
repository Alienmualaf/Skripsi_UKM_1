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
        Schema::create('organization_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Paduan Suara Universitas Pancasila');
            $table->string('alias')->default('PSUP');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('video_url')->nullable();
            $table->string('structure_image')->nullable();
            
            // Open Recruitment fields
            $table->boolean('recruitment_active')->default(false);
            $table->date('recruitment_start_date')->nullable();
            $table->date('recruitment_end_date')->nullable();
            $table->text('recruitment_requirements')->nullable();
            $table->text('recruitment_stages')->nullable();

            // Document downloads
            $table->string('company_profile_pdf')->nullable();
            $table->string('sponsorship_proposal_pdf')->nullable();
            $table->string('media_kit_pdf')->nullable();
            
            $table->timestamps();
        });

        Schema::create('organization_histories', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('title');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        // Insert seed/default profile record
        DB::table('organization_profiles')->insert([
            'name' => 'Paduan Suara Universitas Pancasila',
            'alias' => 'PSUP',
            'tagline' => 'Satu Suara, Sejuta Harmoni',
            'description' => 'Mewadahi minat bakat mahasiswa Universitas Pancasila dalam seni olah suara secara profesional, disiplin, berprestasi, dan terorganisir.',
            'vision' => 'Menjadi paduan suara mahasiswa yang unggul, berprestasi di tingkat nasional maupun internasional, serta menjunjung tinggi nilai harmoni dan kekeluargaan.',
            'mission' => "1. Menyelenggarakan latihan olah vokal secara rutin dan terprogram.\n2. Mengikuti berbagai kompetisi paduan suara tingkat nasional maupun internasional.\n3. Berpartisipasi aktif dalam kegiatan internal dan eksternal Universitas Pancasila.\n4. Mempererat tali persaudaraan antar anggota dan alumni PSUP.",
            'address' => 'Gedung UKM Lt. 2 Universitas Pancasila, Srengseng Sawah, Jagakarsa, Jakarta Selatan',
            'email' => 'psup@univpancasila.ac.id',
            'phone' => '081234567890',
            'instagram' => 'https://instagram.com/psup',
            'youtube' => 'https://youtube.com/psup',
            'tiktok' => 'https://tiktok.com/@psup',
            'website' => 'http://localhost:8000',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_histories');
        Schema::dropIfExists('organization_profiles');
    }
};
