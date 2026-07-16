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
            ->where('performance_date', '>=', now()->toDateString())
            ->orderBy('performance_date', 'asc')
            ->orderBy('performance_time', 'asc')
            ->take(5)->get();

        $announcements = Announcement::with('creator')->latest()->take(5)->get();

        return view('pengurus.dashboard', compact(
            'totalMembers', 'totalPrograms', 'totalPerformances', 'netBalance', 'upcomingAgendas', 'announcements'
        ));
    }

    /**
     * Agenda Kegiatan (Pengurus / Admin UKM)
     */
    public function agenda()
    {
        // Fetch all classroom schedules
        $classroomSchedules = ClassroomSchedule::with('classroom.performance')
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get()
            ->map(function($schedule) {
                $schedule->agenda_type = 'Latihan';
                $schedule->time_display = date('H:i', strtotime($schedule->start_time)) . ($schedule->end_time ? ' - ' . date('H:i', strtotime($schedule->end_time)) : '');
                
                // Link to classroom show
                if ($schedule->classroom) {
                    $perf = $schedule->classroom->performance;
                    if ($perf) {
                        if ($perf->program_id) {
                            $schedule->link = route('pengurus.programs.performance.classroom.show', [$perf->program_id, $perf->id]);
                        } else {
                            $schedule->link = route('pengurus.jobs.classroom.show', $perf->id);
                        }
                    } else {
                        $schedule->link = '#';
                    }
                } else {
                    $schedule->link = '#';
                }
                return $schedule;
            });

        // Fetch all performances
        $performances = Performance::with('classroom')
            ->where('performance_date', '>=', now()->toDateString())
            ->orderBy('performance_date', 'asc')
            ->get()
            ->map(function($perf) {
                $perf->agenda_type = $perf->program_id ? 'Penampilan' : 'Job';
                $perf->date = $perf->performance_date ? $perf->performance_date->format('Y-m-d') : null;
                $perf->time_display = $perf->performance_time ? date('H:i', strtotime($perf->performance_time)) : '17:00';
                
                if ($perf->classroom) {
                    if ($perf->program_id) {
                        $perf->link = route('pengurus.programs.performance.classroom.show', [$perf->program_id, $perf->id]);
                    } else {
                        $perf->link = route('pengurus.jobs.classroom.show', $perf->id);
                    }
                } else {
                    $perf->link = '#';
                }
                return $perf;
            });

        // Fetch non-performance Programs (Proker)
        $prokers = Program::where('activity_type', '!=', 'Performance')
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->get()
            ->map(function($prog) {
                $prog->agenda_type = $prog->activity_type; // Event or Competition
                $prog->title = $prog->name;
                $prog->date = $prog->start_date;
                $prog->time_display = 'All Day';
                $prog->location = $prog->venue;
                $prog->notes = $prog->description;
                $prog->link = route('pengurus.programs.show', $prog->id);
                return $prog;
            });

        // Merge and sort
        $agendas = $classroomSchedules->concat($performances)->concat($prokers)->sortBy('date')->values();

        return view('pengurus.agenda', compact('agendas'));
    }
}