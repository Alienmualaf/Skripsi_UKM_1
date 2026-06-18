<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create letter_categories table
        Schema::create('letter_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // 2. Create inventory_categories table
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // 3. Seed default letter categories
        $defaultLetters = [
            'Surat Tugas',
            'Surat Permohonan',
            'Surat Undangan',
            'Surat Peminjaman',
            'Surat Keterangan'
        ];
        foreach ($defaultLetters as $name) {
            DB::table('letter_categories')->insert([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 4. Seed default inventory categories
        $defaultInventories = [
            'Kostum',
            'Alat Musik',
            'Sound System',
            'Perlengkapan Latihan',
            'Perlengkapan Acara'
        ];
        foreach ($defaultInventories as $name) {
            DB::table('inventory_categories')->insert([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_categories');
        Schema::dropIfExists('letter_categories');
    }
};
