<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Program;
use App\Models\Performance;
use App\Models\Classroom;
use App\Models\Member;
use App\Models\Finance;
use App\Models\Inventory;
use App\Models\Letter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LpjReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function ukm_admin_can_generate_lpj_with_custom_rules()
    {
        $admin = User::where('email', 'admin_ukm@psup.com')->first();
        $this->assertNotNull($admin);

        // 1. Create different types of programs
        $eventProgram = Program::create([
            'name' => 'Konser Internal Event',
            'division' => 'Divisi Latihan',
            'activity_type' => 'Event',
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'venue' => 'Aula Rektorat',
            'pic' => 'Ahmad PIC',
        ]);

        $compeProgram = Program::create([
            'name' => 'Lomba Regional Competition',
            'division' => 'Divisi Humas',
            'activity_type' => 'Competition',
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'venue' => 'Taman Ismail Marzuki',
            'pic' => 'Budi PIC',
        ]);

        // Setup classroom & members for the competition to simulate singer list
        $performance = Performance::create([
            'program_id' => $compeProgram->id,
            'title' => 'Lomba Regional',
            'venue' => 'Taman Ismail Marzuki',
            'performance_date' => now()->toDateString(),
            'status' => 'Selesai',
        ]);

        $classroom = Classroom::create([
            'performance_id' => $performance->id,
            'name' => 'Kelas Lomba Regional',
        ]);

        $member = Member::first();
        $this->assertNotNull($member);
        $memberName = $member->name;

        $classroom->members()->attach($member->id);

        // Create some financials, inventory, and letters to populate the report
        Finance::create([
            'title' => 'Uang Kas Masuk',
            'type' => 'income',
            'amount' => 500000,
            'transaction_date' => now()->toDateString(),
        ]);

        Inventory::create([
            'name' => 'Keyboard Roland',
            'code' => 'INV-KEY-001',
            'category' => 'Alat Musik',
            'condition' => 'Baik',
            'quantity' => 1,
            'storage_location' => 'Sekretariat PSUP',
        ]);

        Letter::create([
            'letter_number' => '001/PSUP/VI/2026',
            'date' => now()->toDateString(),
            'subject' => 'Surat Undangan',
            'destination' => 'UKM Lain',
            'type' => 'Surat Undangan',
            'file_path' => 'letters/test.pdf',
        ]);

        // 2. Request LPJ Web View
        $response = $this->actingAs($admin)->get(route('ukm.reports.lpj', [
            'start_date' => now()->startOfYear()->toDateString(),
            'end_date' => now()->toDateString(),
        ]));

        $response->assertStatus(200);

        // Verify Event Program is rendered but without Singer list header/content
        $response->assertSee('Konser Internal Event');
        $response->assertSee('Ahmad PIC');
        
        // Verify Competition Program is rendered along with its Singer List
        $response->assertSee('Lomba Regional Competition');
        $response->assertSee('Budi PIC');
        $response->assertSee(e($memberName));

        // Verify sections: Keuangan, Inventaris, Persuratan
        $response->assertSee('Rekapitulasi Keuangan Selama Satu Periode');
        $response->assertSee('Uang Kas Masuk');
        $response->assertSee('Keyboard Roland');
        $response->assertSee('001/PSUP/VI/2026');

        // 3. Request LPJ Print View
        $printResponse = $this->actingAs($admin)->get(route('ukm.reports.lpj.print', [
            'start_date' => now()->startOfYear()->toDateString(),
            'end_date' => now()->toDateString(),
        ]));

        $printResponse->assertStatus(200);
        $printResponse->assertSee('Konser Internal Event');
        $printResponse->assertSee('Lomba Regional Competition');
        $printResponse->assertSee(e($memberName));
        $printResponse->assertSee('Keyboard Roland');
        $printResponse->assertSee('001/PSUP/VI/2026');
    }

    /** @test */
    public function event_program_report_and_lpj_excludes_singer_list()
    {
        $admin = User::where('email', 'admin_ukm@psup.com')->first();
        $this->assertNotNull($admin);

        // Create an Event program
        $eventProgram = Program::create([
            'name' => 'Konser Internal Event Only',
            'division' => 'Divisi Latihan',
            'activity_type' => 'Event',
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'venue' => 'Aula Rektorat',
            'pic' => 'Ahmad PIC',
        ]);

        // 1. Check Laporan Kegiatan (Web View)
        $response = $this->actingAs($admin)->get(route('ukm.reports.kegiatan', $eventProgram->id));
        $response->assertStatus(200);
        $response->assertDontSee('2. DAFTAR PENYANYI YANG MENGIKUTI');
        $response->assertDontSee('Belum ada daftar penyanyi yang ditentukan');

        // 2. Check Laporan Kegiatan (Print View)
        $printResponse = $this->actingAs($admin)->get(route('ukm.reports.kegiatan.print', $eventProgram->id));
        $printResponse->assertStatus(200);
        $printResponse->assertDontSee('2. Daftar Penyanyi yang Ikut');

        // 3. Check LPJ (Web View) with only Event program
        $lpjResponse = $this->actingAs($admin)->get(route('ukm.reports.lpj', [
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
        ]));
        $lpjResponse->assertStatus(200);
        // It should contain the event program name
        $lpjResponse->assertSee('Konser Internal Event Only');
        // It should NOT contain the singer list section
        $lpjResponse->assertDontSee('2. Daftar Penyanyi yang Ikut');

        // 4. Check LPJ (Print View)
        $lpjPrintResponse = $this->actingAs($admin)->get(route('ukm.reports.lpj.print', [
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
        ]));
        $lpjPrintResponse->assertStatus(200);
        $lpjPrintResponse->assertSee('Konser Internal Event Only');
        $lpjPrintResponse->assertDontSee('2. DAFTAR PENYANYI YANG IKUT');
    }
}
