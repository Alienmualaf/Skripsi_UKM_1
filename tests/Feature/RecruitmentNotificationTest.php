<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\NotificationLog;
use App\Models\Role;
use App\Models\User;
use App\Models\VoiceClassification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruitmentNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the database using DatabaseSeeder
        $this->seed();
    }

    /** @test */
    public function candidate_can_register_and_admin_can_approve_them_with_email_and_wa_logs()
    {
        // 1. Submit Registration Form
        $registrationData = [
            'name' => 'Budi Prasetyo',
            'npm' => '4520230099',
            'gender' => 'L',
            'faculty' => 'Teknik',
            'major' => 'Informatika',
            'class_year' => '2023',
            'phone' => '081234567899',
            'email' => 'budi.prasetyo@example.com',
            'choir_experience' => 'Paduan suara sekolah 3 tahun',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post(route('register-candidate.submit'), $registrationData);

        $response->assertRedirect(route('register-candidate'));
        $response->assertSessionHas('success');

        // Assert User and Member were created
        $user = User::where('email', 'budi.prasetyo@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('pending', $user->status);

        $member = Member::where('npm', '4520230099')->first();
        $this->assertNotNull($member);
        $this->assertEquals('Calon Anggota', $member->status);
        $this->assertEquals($user->id, $member->user_id);
        $this->assertEquals('Paduan suara sekolah 3 tahun', $member->choir_experience);

        // 2. Admin Logs In and Approves the Candidate
        $admin = User::where('email', 'admin_ukm@psup.com')->first();
        $this->assertNotNull($admin);

        $voiceClass = VoiceClassification::where('name', 'Tenor')->first();
        $this->assertNotNull($voiceClass);

        $verifyResponse = $this->actingAs($admin)
            ->post(route('ukm.registrations.verify', $member->id), [
                'action' => 'Terima',
                'voice_classification_id' => $voiceClass->id,
            ]);

        $verifyResponse->assertRedirect();
        $verifyResponse->assertSessionHas('success');

        // Check user & member status after approval
        $user->refresh();
        $member->refresh();

        $this->assertEquals('active', $user->status);
        $this->assertEquals('Anggota Aktif', $member->status);
        $this->assertEquals($voiceClass->id, $member->voice_classification_id);

        // Check Notification Logs (Email + WA)
        $emailLog = NotificationLog::where('user_id', $user->id)->where('type', 'email')->first();
        $this->assertNotNull($emailLog);
        if ($emailLog->status !== 'success') {
            dump($emailLog->error_message);
        }
        $this->assertEquals('success', $emailLog->status);
        $this->assertStringContainsString('budi.prasetyo@example.com', $emailLog->recipient);
        $this->assertStringContainsString('Budi Prasetyo', $emailLog->recipient_name);
        $this->assertStringContainsString('Tenor', $emailLog->content);

        $waLog = NotificationLog::where('user_id', $user->id)->where('type', 'whatsapp')->first();
        $this->assertNotNull($waLog);
        $this->assertEquals('success', $waLog->status);
        $this->assertEquals('081234567899', $waLog->recipient);
        $this->assertStringContainsString('Tenor', $waLog->content);
        $this->assertStringContainsString('PSUP-', $waLog->content); // Temporary password format
    }

    /** @test */
    public function candidate_can_be_rejected_and_receives_rejection_logs()
    {
        // 1. Submit Registration Form
        $registrationData = [
            'name' => 'Budi Prasetyo',
            'npm' => '4520230099',
            'gender' => 'L',
            'faculty' => 'Teknik',
            'major' => 'Informatika',
            'class_year' => '2023',
            'phone' => '081234567899',
            'email' => 'budi.prasetyo@example.com',
            'choir_experience' => 'Paduan suara sekolah 3 tahun',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post(route('register-candidate.submit'), $registrationData);

        $user = User::where('email', 'budi.prasetyo@example.com')->first();
        $member = Member::where('npm', '4520230099')->first();

        // 2. Admin Logs In and Rejects
        $admin = User::where('email', 'admin_ukm@psup.com')->first();

        $verifyResponse = $this->actingAs($admin)
            ->post(route('ukm.registrations.verify', $member->id), [
                'action' => 'Tolak',
            ]);

        $verifyResponse->assertRedirect();

        // Check user & member status after rejection
        $user->refresh();
        $member->refresh();

        $this->assertEquals('inactive', $user->status);
        $this->assertEquals('Ditolak', $member->status);

        // Check Notification Logs (Email + WA)
        $emailLog = NotificationLog::where('user_id', $user->id)->where('type', 'email')->first();
        $this->assertNotNull($emailLog);
        $this->assertEquals('success', $emailLog->status);
        $this->assertStringContainsString('budi.prasetyo@example.com', $emailLog->recipient);
        $this->assertStringContainsString('Pemberitahuan Hasil Pendaftaran', $emailLog->subject);

        $waLog = NotificationLog::where('user_id', $user->id)->where('type', 'whatsapp')->first();
        $this->assertNotNull($waLog);
        $this->assertEquals('success', $waLog->status);
        $this->assertEquals('081234567899', $waLog->recipient);
        $this->assertStringContainsString('belum dapat kami terima', $waLog->content);
    }
}
