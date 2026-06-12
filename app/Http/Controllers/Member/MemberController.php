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
        $user = auth()->user();
        $member = $user->member;

        if (!$member) {
            return redirect('/')->with('error', 'Profil anggota tidak ditemukan.');
        }

        $classroomIds = $member->classrooms()->pluck('classrooms.id')->toArray();
        $upcomingAgendas = \App\Models\ClassroomSchedule::whereIn('classroom_id', $classroomIds)
            ->where('date', '>=', date('Y-m-d'))
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(3)->get();

        $announcements = Announcement::with('creator')->latest()->take(3)->get();

        // Jobs assigned to this member
        $activeJobs = $member->jobs()->where('performance_date', '>=', date('Y-m-d'))->orderBy('performance_date', 'asc')->get();

        // Stats calculations
        $totalPresent = \App\Models\AttendanceDetail::where('member_id', $member->id)->where('status', 'Hadir')->count();
        $totalAbsent = \App\Models\AttendanceDetail::where('member_id', $member->id)->whereIn('status', ['Alpha', 'Alfa', 'Tidak Hadir'])->count();
        $activeJobsCount = $member->jobs()->where('performance_date', '>=', date('Y-m-d'))->count();
        $activeClassroomsCount = count($classroomIds);

        $stats = [
            'total_present' => $totalPresent,
            'total_absent' => $totalAbsent,
            'active_jobs' => $activeJobsCount,
            'active_classrooms' => $activeClassroomsCount,
        ];

        $voice = $member->voiceClassification ? $member->voiceClassification->name : 'Belum Diklasifikasi';
        $recentMaterials = Material::latest()->take(3)->get();
        $classrooms = $member->classrooms()->with('performance')->latest()->get();

        return view('member.dashboard', compact('member', 'upcomingAgendas', 'announcements', 'activeJobs', 'stats', 'voice', 'recentMaterials', 'classrooms'));
    }

    /**
     * Manage Personal Profile
     */
    public function profile()
    {
        $member = auth()->user()->member;
        $classifications = VoiceClassification::all();
        return view('member.profile', compact('member', 'classifications'));
    }

    public function updateProfile(Request $request)
    {
        $member = auth()->user()->member;

        $data = $request->validate([
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('members', 'public');
            $data['photo'] = $path;
        }

        $member->update($data);

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
        if ($currentFolderId) {
            $folder = Folder::findOrFail($currentFolderId);
            $breadcrumbs[] = $folder;
            $parent = $folder->parent;
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

        return view('member.materials.index', compact('folders', 'materials', 'breadcrumbs', 'currentFolderId', 'search', 'type'));
    }


}
