<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ActivitySession;
use App\Models\Membership;
use App\Models\Coach;
use App\Models\CoachAttendance;
use Illuminate\Http\Request;

class AttendanceReportController extends Controller
{
    /**
     * Rekap Absensi Anggota per Agenda (Event)
     */
    public function memberRekap($eventId)
    {
        $ukmId = session('managed_ukm_id');
        $event = Event::where('id', $eventId)->where('ukm_id', $ukmId)->firstOrFail();
        
        // Fetch all events of this UKM for agenda filter dropdown
        $events = Event::where('ukm_id', $ukmId)->orderBy('start_date', 'desc')->get();
        
        $sessions = ActivitySession::where('event_id', $eventId)->orderBy('date', 'asc')->get();
        $totalSessions = $sessions->count();
        
        $participants = $event->participants()->with('user', 'classification')->get();
        
        $rekap = $participants->map(function ($membership) use ($sessions, $totalSessions) {
            $attendances = \App\Models\Attendance::whereIn('session_id', $sessions->pluck('id'))
                ->where('user_id', $membership->user_id)
                ->get();
            
            $hadir = $attendances->where('status', 'hadir')->count();
            $izin = $attendances->where('status', 'izin')->count();
            $tidakHadir = $attendances->where('status', 'tidak hadir')->count();
            
            $persentase = $totalSessions > 0 ? ($hadir / $totalSessions) * 100 : 0;
            
            return [
                'nama' => $membership->user->name,
                'klasifikasi' => optional($membership->classification)->name ?? '-',
                'hadir' => $hadir,
                'izin' => $izin,
                'tidak_hadir' => $tidakHadir,
                'persentase' => round($persentase, 2)
            ];
        });

        return view('ukm.reports.member_attendance_rekap', compact('event', 'events', 'rekap', 'totalSessions'));
    }

    /**
     * Rekap Absensi Pelatih (Single & Multi Event)
     */
    public function coachRekap($eventId, Request $request)
    {
        $ukmId = session('managed_ukm_id');
        $event = Event::where('id', $eventId)->where('ukm_id', $ukmId)->firstOrFail();
        
        // Fetch all events of this UKM for agenda filter dropdown
        $events = Event::where('ukm_id', $ukmId)->orderBy('start_date', 'desc')->get();
        
        // Get date filters for Multi-Event Range
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        $isMultiEvent = !empty($startDate) && !empty($endDate);
        
        if ($isMultiEvent) {
            // MULTI-EVENT RECAP (Rekap Keseluruhan by Period)
            $ukmEventIds = Event::where('ukm_id', $ukmId)->pluck('id');
            $sessions = ActivitySession::whereIn('event_id', $ukmEventIds)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'asc')
                ->get();
        } else {
            // SINGLE-EVENT RECAP
            $sessions = ActivitySession::where('event_id', $eventId)->orderBy('date', 'asc')->get();
        }
        
        $totalSessions = $sessions->count();
        $sessionIds = $sessions->pluck('id');
        
        // Fetch all registered coaches and BPH in the UKM
        $coaches = Coach::where('ukm_id', $ukmId)->get();
        
        $rekapData = [];
        
        // 1. Calculate stats for registered coaches & BPH
        foreach ($coaches as $coach) {
            $attendances = CoachAttendance::with('session')
                ->whereIn('session_id', $sessionIds)
                ->where('coach_id', $coach->id)
                ->get();
            
            $hadir = $attendances->where('status', 'hadir')->count();
            $izin = $attendances->where('status', 'izin')->count();
            $tidakHadir = $attendances->where('status', 'tidak hadir')->count();
            
            $persentase = $totalSessions > 0 ? ($hadir / $totalSessions) * 100 : 0;
            
            $hadirSessions = $attendances->where('status', 'hadir')->map(function ($att) {
                return [
                    'title' => $att->session->title,
                    'date' => $att->session->date->format('d M Y')
                ];
            })->values()->toArray();
            
            $rekapData[] = [
                'nama' => $coach->name,
                'kategori' => $coach->category,
                'hadir' => $hadir,
                'izin' => $izin,
                'tidak_hadir' => $tidakHadir,
                'persentase' => round($persentase, 2),
                'hadir_sessions' => $hadirSessions
            ];
        }
        
        // 2. Calculate stats for unregistered/temporary coaches ("Lainnya")
        $manualAttendances = CoachAttendance::with('session')
            ->whereIn('session_id', $sessionIds)
            ->whereNull('coach_id')
            ->where('category', 'lainnya')
            ->get();
            
        $manualGrouped = $manualAttendances->groupBy('name');
        
        foreach ($manualGrouped as $name => $atts) {
            $hadir = $atts->where('status', 'hadir')->count();
            $izin = $atts->where('status', 'izin')->count();
            $tidakHadir = $atts->where('status', 'tidak hadir')->count();
            
            $persentase = $totalSessions > 0 ? ($hadir / $totalSessions) * 100 : 0;
            
            $hadirSessions = $atts->where('status', 'hadir')->map(function ($att) {
                return [
                    'title' => $att->session->title,
                    'date' => $att->session->date->format('d M Y')
                ];
            })->values()->toArray();
            
            $rekapData[] = [
                'nama' => $name,
                'kategori' => 'lainnya',
                'hadir' => $hadir,
                'izin' => $izin,
                'tidak_hadir' => $tidakHadir,
                'persentase' => round($persentase, 2),
                'hadir_sessions' => $hadirSessions
            ];
        }
        
        // 3. Category Filter
        $categoryFilter = $request->query('category', 'all');
        if ($categoryFilter !== 'all') {
            $rekapData = array_filter($rekapData, function ($item) use ($categoryFilter) {
                return $item['kategori'] === $categoryFilter;
            });
        }
        
        // Convert array back to simple indexed collection or array
        $rekap = collect(array_values($rekapData));
        
        return view('ukm.reports.coach_attendance_rekap', compact(
            'event', 'events', 'rekap', 'totalSessions', 'isMultiEvent', 'startDate', 'endDate', 'categoryFilter'
        ));
    }
}
