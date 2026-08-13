<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Announcement;

use App\Models\Folder;
use App\Models\Material;
use App\Models\VoiceClassification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    /**
     * Anggota Dashboard
     */
    public function dashboard()
    {
        // Auto-archive past classrooms
        \App\Models\Classroom::autoArchivePastClassrooms();

        $user = auth()->user();
        $member = $user->member;

        if (!$member) {
            if ($user->isSuperAdmin()) {
                $member = Member::first() ?? new Member([
                    'name' => $user->name,
                    'birth_place' => 'Jakarta',
                    'birth_date' => '2000-01-01',
                    'address' => 'Pancasila University',
                    'phone' => '08123456789',
                    'status' => 'Anggota Aktif'
                ]);
            } else {
                return redirect('/')->with('error', 'Profil anggota tidak ditemukan.');
            }
        }

        $classroomIds = $member->classrooms()->where('classrooms.status', 'Aktif')->pluck('classrooms.id')->toArray();
        $upcomingAgendas = \App\Models\ClassroomSchedule::whereIn('classroom_id', $classroomIds)
            ->where('date', '>=', date('Y-m-d'))
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(3)->get();

        $announcements = Announcement::with('creator')->latest()->take(3)->get();

        // Total performances participated by this member (including archived classrooms/past events)
        $activeJobs = $member->jobs()
            ->orderBy('performance_date', 'desc')
            ->get();

        // Stats calculations
        $totalPresent = \App\Models\AttendanceDetail::where('member_id', $member->id)->where('status', 'Hadir')->count();
        $totalAbsent = \App\Models\AttendanceDetail::where('member_id', $member->id)->whereIn('status', ['Alpha', 'Alfa', 'Tidak Hadir'])->count();
        $activeJobsCount = $activeJobs->count();
        $activeClassroomsCount = count($classroomIds);

        $stats = [
            'total_present' => $totalPresent,
            'total_absent' => $totalAbsent,
            'active_jobs' => $activeJobsCount,
            'active_classrooms' => $activeClassroomsCount,
        ];

        $voice = $member->voiceClassification ? $member->voiceClassification->name : 'Belum Diklasifikasi';
        $recentMaterials = Material::latest()->take(3)->get();
        $classrooms = $member->classrooms()->where('classrooms.status', 'Aktif')->with('performance')->latest()->get();

        return view('member.dashboard', compact('member', 'upcomingAgendas', 'announcements', 'activeJobs', 'stats', 'voice', 'recentMaterials', 'classrooms'));
    }

    /**
     * Manage Personal Profile
     */
    public function profile()
    {
        $user = auth()->user();
        $member = $user->member;
        if (!$member) {
            $member = new Member([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'npm' => '-',
                'gender' => 'L',
                'faculty' => '-',
                'major' => '-',
                'class_year' => date('Y'),
                'birth_place' => '-',
                'birth_date' => date('Y-m-d'),
                'address' => '-',
                'phone' => '-',
                'status' => 'Anggota Aktif'
            ]);
        }
        $classifications = VoiceClassification::all();
        return view('member.profile', compact('member', 'classifications'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $member = $user->member;

        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ];

        if ($user->isAnggota()) {
            $rules['nim'] = 'required|string|max:50';
            $rules['faculty'] = 'required|string|max:100';
        }

        $data = $request->validate($rules);

        // Update User
        $user->name = $data['name'];
        if (!empty($data['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($data['password']);
        }
        $user->save();

        if ($member) {
            $memberData = [
                'name' => $data['name'],
                'phone' => $data['phone'],
            ];

            if ($user->isAnggota()) {
                $memberData['npm'] = $data['nim'];
                $memberData['faculty'] = $data['faculty'];
            }

            if (isset($data['birth_place'])) $memberData['birth_place'] = $data['birth_place'];
            if (isset($data['birth_date'])) $memberData['birth_date'] = $data['birth_date'];
            if (isset($data['address'])) $memberData['address'] = $data['address'];

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('members', 'public');
                $memberData['photo'] = $path;
            }

            $member->update($memberData);
        }

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Materials Browse (Read-only Google Drive view)
     */
    public function materials(Request $request)
    {
        $currentFolderId = $request->query('folder_id');
        $search = $request->query('search');
        $type = $request->query('type'); // Partitur, Audio, Video

        // Breadcrumbs
        $breadcrumbs = [];
        $currentFolder = null;
        if ($currentFolderId) {
            $currentFolder = Folder::findOrFail($currentFolderId);
            $breadcrumbs[] = $currentFolder;
            $parent = $currentFolder->parent;
            while ($parent) {
                array_unshift($breadcrumbs, $parent);
                $parent = $parent->parent;
            }
        }

        // Folders list
        $foldersQuery = Folder::query();
        if ($currentFolderId) {
            $foldersQuery->where('parent_id', $currentFolderId);
        } else {
            $foldersQuery->whereNull('parent_id');
        }
        if ($search) {
            $foldersQuery->where('name', 'like', "%{$search}%");
        }
        $folders = $foldersQuery->orderBy('name', 'asc')->get();

        // Materials list
        $materialsQuery = Material::query();
        if ($currentFolderId) {
            $materialsQuery->where('folder_id', $currentFolderId);
        } else {
            $materialsQuery->whereNull('folder_id');
        }
        if ($search) {
            $materialsQuery->where('title', 'like', "%{$search}%");
        }
        if ($type) {
            $materialsQuery->where('type', $type);
        }
        $materials = $materialsQuery->orderBy('title', 'asc')->get();

        return view('member.materials.index', compact('folders', 'materials', 'breadcrumbs', 'currentFolder', 'currentFolderId', 'search', 'type'));
    }

    /**
     * Agenda Saya
     */
    public function agenda()
    {
        $user = auth()->user();
        $member = $user->member;
        
        if (!$member) {
            return redirect('/')->with('error', 'Profil anggota tidak ditemukan.');
        }

        $classroomIds = $member->classrooms()->pluck('classrooms.id')->toArray();

        // Fetch schedules of classrooms the member belongs to
        $classroomSchedules = \App\Models\ClassroomSchedule::whereIn('classroom_id', $classroomIds)
            ->with('classroom')
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get()
            ->map(function($schedule) {
                $schedule->agenda_type = 'Latihan';
                $schedule->time_display = date('H:i', strtotime($schedule->start_time)) . ($schedule->end_time ? ' - ' . date('H:i', strtotime($schedule->end_time)) : '');
                $schedule->link = route('member.classrooms.show', $schedule->classroom_id);
                return $schedule;
            });

        // Fetch performances/jobs assigned to this member (including archived classrooms)
        $performances = $member->jobs()
            ->where('performance_date', '>=', now()->toDateString())
            ->orderBy('performance_date', 'asc')
            ->get()
            ->map(function($perf) {
                $perf->agenda_type = $perf->program_id ? 'Penampilan' : 'Job';
                $perf->date = $perf->performance_date ? $perf->performance_date->format('Y-m-d') : null;
                $perf->time_display = $perf->performance_time ? date('H:i', strtotime($perf->performance_time)) : '17:00';
                $perf->link = $perf->classroom ? route('member.classrooms.show', $perf->classroom->id) : '#';
                return $perf;
            });

        // Fetch non-performance Programs (Proker)
        $prokers = \App\Models\Program::where('activity_type', '!=', 'Performance')
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
                $prog->link = '#';
                return $prog;
            });

        // Merge and sort by date
        $agendas = $classroomSchedules->concat($performances)->concat($prokers)->sortBy('date')->values();

        return view('member.agenda', compact('agendas'));
    }
}
