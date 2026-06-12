<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Performance;
use App\Models\Member;
use App\Models\InventoryLoan;
use App\Models\Letter;
use App\Models\ClassroomSchedule;

use App\Models\Finance;
use App\Models\ProgramReport;
use App\Models\Announcement;

class DashboardController extends Controller
{
    /**
     * Pengurus Dashboard
     */
    public function dashboard()
    {
        $totalMembers = Member::where('status', 'Anggota Aktif')->count();
        $totalPrograms = Program::where('status', 'Berjalan')->count();
        $totalPerformances = Performance::where('status', '!=', 'Selesai')->count();
        
        $totalIncome = Finance::where('type', 'income')->sum('amount');
        $totalExpense = Finance::where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        // Fetch upcoming performances as agendas
        $upcomingAgendas = Performance::where('status', '!=', 'Selesai')
            ->orderBy('performance_date', 'asc')
            ->orderBy('performance_time', 'asc')
            ->take(5)->get();

        $announcements = Announcement::with('creator')->latest()->take(5)->get();

        return view('pengurus.dashboard', compact(
            'totalMembers', 'totalPrograms', 'totalPerformances', 'netBalance', 'upcomingAgendas', 'announcements'
        ));
    }
}