<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\ClassroomSchedule;
use App\Models\ClassroomSongTarget;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClassroomController extends Controller
{
    /**
     * Tampilkan menu "Classroom Saya" yang berisi daftar Classroom berdasarkan Penampilan yang diikuti.
     */
    public function index()
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('member.dashboard')->with('error', 'Profil anggota tidak ditemukan.');
        }

        // Ambil daftar classroom yang diikuti oleh anggota
        $classrooms = $member->classrooms()->with(['performance'])->get();

        return view('member.classroom.index', compact('classrooms'));
    }

    /**
     * Tampilkan detail Classroom (Informasi, Materi, Peserta, Absensi)
     */
    public function show($classroomId, Request $request)
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            abort(403, 'Akses ditolak.');
        }

        $classroom = Classroom::with(['performance', 'members', 'materials', 'attendances', 'announcements', 'schedules', 'songTargets'])
            ->findOrFail($classroomId);

        // Validasi keikutsertaan anggota di classroom tersebut
        if (!$classroom->members()->where('member_id', $member->id)->exists()) {
            abort(403, 'Anda bukan peserta di classroom ini.');
        }

        $tab = $request->query('tab', 'stream');

        // Load stream (pengumuman)
        $announcements = $classroom->announcements()->with('creator')->latest()->get();

        // Load materi
        $materials = $classroom->materials()->latest()->get();

        // Load peserta
        $participants = $classroom->members()->with(['user', 'voiceClassification'])->get();

        // Load jadwal latihan
        $schedules = $classroom->schedules()->orderBy('date', 'asc')->orderBy('start_time', 'asc')->get();

        // Load target lagu / penugasan suara
        $songTargets = $classroom->songTargets()->get();

        // Load absensi khusus untuk member ini
        $attendances = $classroom->attendances()
            ->with(['details' => function ($q) use ($member) {
                $q->where('member_id', $member->id);
            }])
            ->orderBy('date', 'desc')
            ->get();

        $totalSessions = $classroom->attendances()->count();
        $hadirCount = 0;
        $izinCount = 0;
        $sakitCount = 0;
        $tidakHadirCount = 0;

        foreach ($attendances as $att) {
            $detail = $att->details->first();
            if ($detail) {
                $status = strtolower($detail->status);
                if ($status === 'hadir') {
                    $hadirCount++;
                } elseif ($status === 'izin') {
                    $izinCount++;
                } elseif ($status === 'sakit') {
                    $sakitCount++;
                } elseif (in_array($status, ['alpha', 'alpa', 'tidak hadir'])) {
                    $tidakHadirCount++;
                }
            }
        }

        $persentase = $totalSessions > 0 ? (($hadirCount + $izinCount + $sakitCount) / $totalSessions) * 100 : 0;
        $persentase = round($persentase, 2);

        return view('member.classroom.classroom', compact(
            'classroom',
            'tab',
            'announcements',
            'materials',
            'participants',
            'schedules',
            'songTargets',
            'attendances',
            'totalSessions',
            'hadirCount',
            'izinCount',
            'sakitCount',
            'tidakHadirCount',
            'persentase'
        ));
    }

    /**
     * Download material file
     */
    public function downloadMaterial($id)
    {
        $material = Material::findOrFail($id);

        if ($material->file_path) {
            return Storage::disk('public')->download($material->file_path, $material->title);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }
}
