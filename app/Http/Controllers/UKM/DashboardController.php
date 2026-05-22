<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Finance;
use App\Models\Membership;
use App\Models\UKM;
use App\Models\Inventory;
use App\Models\Material;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $ukmId = session('managed_ukm_id');
        $ukm = UKM::findOrFail($ukmId);

        // Keanggotaan
        $totalMembers = Membership::where('ukm_id', $ukmId)
            ->where('status', 'approved')
            ->where('role_in_ukm', '!=', 'admin')
            ->count();

        $pendingMembers = Membership::where('ukm_id', $ukmId)
            ->where('status', 'pending')->count();

        // Keanggotaan Peran
        $adminCount = Membership::where('ukm_id', $ukmId)
            ->where('status', 'approved')
            ->where('role_in_ukm', 'admin')
            ->count();
            
        $memberCount = $totalMembers;

        // Komposisi berdasarkan Klasifikasi (diatur admin di Profil UKM)
        $classifications = \App\Models\UKMClassification::where('ukm_id', $ukmId)->get();
        $classificationLabels = [];
        $classificationCounts = [];

        foreach ($classifications as $c) {
            $count = Membership::where('ukm_id', $ukmId)
                ->where('status', 'approved')
                ->where('ukm_classification_id', $c->id)
                ->count();
            $classificationLabels[] = $c->name;
            $classificationCounts[] = $count;
        }

        // Anggota tanpa klasifikasi
        $unclassifiedCount = Membership::where('ukm_id', $ukmId)
            ->where('status', 'approved')
            ->where('role_in_ukm', '!=', 'admin')
            ->whereNull('ukm_classification_id')
            ->count();
        $classificationLabels[] = 'Belum Ditentukan';
        $classificationCounts[] = $unclassifiedCount;

        // Kegiatan & Keuangan
        $totalEvents = Event::where('ukm_id', $ukmId)->count();
        $totalIncome = Finance::where('ukm_id', $ukmId)->where('type', 'income')->sum('amount');
        $totalExpense = Finance::where('ukm_id', $ukmId)->where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        // Barang & Bahan Ajar
        $totalInventories = Inventory::where('ukm_id', $ukmId)->sum('quantity') ?: 0;
        $totalMaterials = Material::where('ukm_id', $ukmId)->count();

        // Riwayat Kegiatan & Pengajuan
        $events = Event::where('ukm_id', $ukmId)->latest()->take(5)->get();

        return view('ukm.dashboard', compact(
            'ukm',
            'totalMembers',
            'pendingMembers',
            'adminCount',
            'memberCount',
            'classificationLabels',
            'classificationCounts',
            'totalEvents',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'totalInventories',
            'totalMaterials',
            'events'
        ));
    }
}