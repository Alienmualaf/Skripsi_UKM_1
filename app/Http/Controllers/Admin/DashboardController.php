<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UKM;
use App\Models\User;
use App\Models\Membership;
use App\Models\Event;
use App\Models\Finance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUKM = UKM::count();
        $totalUsers = User::count();
        $totalMembers = Membership::where('status', 'approved')->count();
        $totalEvents = Event::count();

        // Keuangan Konsolidasi
        $totalIncome = Finance::where('type', 'income')->sum('amount');
        $totalExpense = Finance::where('type', 'expense')->sum('amount');
        $globalBalance = $totalIncome - $totalExpense;

        // Data UKM Terperinci
        $ukms = UKM::with(['events', 'finances', 'memberships' => function($q) {
            $q->where('status', 'approved');
        }])->get();

        $ukmData = [];
        foreach ($ukms as $ukm) {
            $income = $ukm->finances->where('type', 'income')->sum('amount');
            $expense = $ukm->finances->where('type', 'expense')->sum('amount');
            $balance = $income - $expense;

            $ukmData[] = [
                'id' => $ukm->id,
                'name' => $ukm->name,
                'members_count' => $ukm->memberships->count(),
                'events_count' => $ukm->events->count(),
                'income' => $income,
                'expense' => $expense,
                'balance' => $balance
            ];
        }

        // Live Log Aktivitas Terbaru
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUKM',
            'totalUsers',
            'totalMembers',
            'totalEvents',
            'totalIncome',
            'totalExpense',
            'globalBalance',
            'ukmData',
            'recentUsers'
        ));
    }
}