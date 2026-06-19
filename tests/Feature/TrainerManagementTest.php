<?php

namespace Tests\Feature;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function ukm_admin_can_add_edit_and_delete_trainers()
    {
        // 1. Get UKM Admin User
        $admin = User::where('email', 'admin_ukm@psup.com')->first();
        $this->assertNotNull($admin);

        // 2. Add Trainer
        $trainerData = [
            'name' => 'John Doe, M.Mus.',
            'specialty' => 'Dirigen Vokal',
            'phone' => '08123456789',
            'email' => 'johndoe@example.com',
            'salary' => 1500000,
            'status' => 'Aktif',
        ];

        $response = $this->actingAs($admin)
            ->post(route('ukm.trainers.store'), $trainerData);

        $response->assertRedirect(route('ukm.trainers.index'));
        $this->assertDatabaseHas('trainers', [
            'name' => 'John Doe, M.Mus.',
            'specialty' => 'Dirigen Vokal',
        ]);

        $trainer = Trainer::where('email', 'johndoe@example.com')->first();
        $this->assertNotNull($trainer);

        // 3. Edit Trainer
        $updateData = [
            'name' => 'John Doe updated',
            'specialty' => 'Pianis',
            'phone' => '08987654321',
            'email' => 'johnupdate@example.com',
            'salary' => 1800000,
            'status' => 'Aktif',
        ];

        $response = $this->actingAs($admin)
            ->post(route('ukm.trainers.update', $trainer->id), $updateData);

        $response->assertRedirect(route('ukm.trainers.index'));
        $this->assertDatabaseHas('trainers', [
            'id' => $trainer->id,
            'name' => 'John Doe updated',
            'specialty' => 'Pianis',
        ]);

        // 4. Delete Trainer
        $response = $this->actingAs($admin)
            ->delete(route('ukm.trainers.destroy', $trainer->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('trainers', [
            'id' => $trainer->id,
        ]);
    }
}
