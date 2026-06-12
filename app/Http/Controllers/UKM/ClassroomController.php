<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Announcement;
use App\Models\ClassroomSchedule;
use App\Models\ClassroomSongTarget;
use App\Models\Performance;
use App\Models\Member;
use App\Models\Material;
use App\Models\Program;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        // Self-healing: auto-create Performance & Classroom for any Program of type 'Performance' that doesn't have it
        $programsWithoutPerformance = Program::where('activity_type', 'Performance')
            ->whereDoesntHave('performance')
            ->get();

        foreach ($programsWithoutPerformance as $program) {
            $performance = $program->performance()->create([
                'title'            => 'Penampilan ' . $program->name,
                'venue'            => 'Belum ditentukan',
                'performance_date' => $program->start_date,
                'status'           => 'Persiapan',
            ]);

            $performance->classroom()->create([
                'name'   => 'Classroom ' . $performance->title,
                'status' => 'Aktif',
            ]);
        }

        // Also ensure any existing Performance has a Classroom
        $performancesWithoutClassroom = Performance::whereDoesntHave('classroom')->get();
        foreach ($performancesWithoutClassroom as $perf) {
            $perf->classroom()->create([
                'name'   => 'Classroom ' . $perf->title,
                'status' => 'Aktif',
            ]);
        }

        // Self-healing for Jobs: auto-create Classroom for any external Performance that doesn't have it
        $jobsWithoutClassroom = Performance::whereNull('program_id')->whereDoesntHave('classroom')->get();
        foreach ($jobsWithoutClassroom as $job) {
            $classroom = $job->classroom()->create([
                'name'   => 'Classroom ' . $job->title,
                'status' => 'Aktif',
            ]);
        }

        $classrooms = Classroom::with(['performance.program', 'members', 'attendances'])->latest()->get();
        $performances = Performance::with('program')->latest()->get();
        return view('pengurus.classrooms.index', compact('classrooms', 'performances'));
    }

    /**
     * Show classroom for a given performance.
     */
    public function show($programId, $performanceId)
    {
        $performance = Performance::with([
            'classroom.members.voiceClassification',
            'classroom.materials',
            'classroom.attendances',
            'classroom.announcements.creator',
            'classroom.schedules',
            'classroom.songTargets',
        ])->where('program_id', $programId)->findOrFail($performanceId);

        $classroom = $performance->classroom;

        // Auto-create classroom if doesn't exist
        if (!$classroom) {
            $classroom = Classroom::create([
                'performance_id' => $performanceId,
                'name'           => 'Classroom ' . $performance->title,
                'status'         => 'Aktif',
            ]);
            $classroom->load(['members', 'materials', 'attendances', 'announcements', 'schedules', 'songTargets']);
        }

        $allMembers = Member::where('status', 'Anggota Aktif')->with('voiceClassification')->get();
        $allMaterials = Material::with('uploader')->orderBy('type')->orderBy('title')->get();
        $classroomMemberIds = $classroom->members->pluck('id')->toArray();

        return view('pengurus.classrooms.show', compact(
            'performance', 'classroom', 'allMembers', 'allMaterials', 'classroomMemberIds', 'programId'
        ));
    }

    /**
     * Show classroom for a given Job.
     */
    public function showJobClassroom($jobId)
    {
        $job = Performance::whereNull('program_id')->with([
            'classroom.members.voiceClassification',
            'classroom.materials',
            'classroom.attendances',
            'classroom.announcements.creator',
            'classroom.schedules',
            'classroom.songTargets',
        ])->findOrFail($jobId);

        $classroom = $job->classroom;

        if (!$classroom) {
            $classroom = Classroom::create([
                'performance_id' => $jobId,
                'name'           => 'Classroom ' . $job->title,
                'status'         => 'Aktif',
            ]);
            $classroom->load(['members', 'materials', 'attendances', 'announcements', 'schedules', 'songTargets']);
        }

        $allMembers = Member::where('status', 'Anggota Aktif')->with('voiceClassification')->get();
        $allMaterials = Material::with('uploader')->orderBy('type')->orderBy('title')->get();
        $classroomMemberIds = $classroom->members->pluck('id')->toArray();

        return view('pengurus.classrooms.show', compact(
            'job', 'classroom', 'allMembers', 'allMaterials', 'classroomMemberIds'
        ));
    }

    // Helper to get Classroom
    private function getCommonClassroom($programId, $performanceId, $jobId)
    {
        if ($jobId) {
            $job = Performance::whereNull('program_id')->findOrFail($jobId);
            return $job->classroom ?? $job->classroom()->create([
                'name'   => 'Classroom ' . $job->title,
                'status' => 'Aktif',
            ]);
        }
        $performance = Performance::where('program_id', $programId)->findOrFail($performanceId);
        return $performance->classroom ?? $performance->classroom()->create([
            'name'   => 'Classroom ' . $performance->title,
            'status' => 'Aktif',
        ]);
    }

    // --- Dynamic Sync Members ---
    public function syncMembers(Request $request, $programId, $performanceId)
    {
        return $this->processSyncMembers($request, $this->getCommonClassroom($programId, $performanceId, null));
    }

    public function syncJobMembers(Request $request, $jobId)
    {
        return $this->processSyncMembers($request, $this->getCommonClassroom(null, null, $jobId));
    }

    private function processSyncMembers(Request $request, Classroom $classroom)
    {
        $request->validate([
            'member_ids'   => 'nullable|array',
            'member_ids.*' => 'exists:members,id',
        ]);
        $classroom->members()->sync($request->input('member_ids', []));
        return back()->with('success', 'Peserta classroom berhasil diperbarui.');
    }

    // --- Dynamic Add Material ---
    public function addMaterial(Request $request, $programId, $performanceId)
    {
        return $this->processAddMaterial($request, $this->getCommonClassroom($programId, $performanceId, null));
    }

    public function addJobMaterial(Request $request, $jobId)
    {
        return $this->processAddMaterial($request, $this->getCommonClassroom(null, null, $jobId));
    }

    private function processAddMaterial(Request $request, Classroom $classroom)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
        ]);
        $material = Material::findOrFail($request->material_id);
        $material->update(['classroom_id' => $classroom->id]);
        
        if ($request->input('redirect_to_materials')) {
            $currentFolderId = $material->folder_id;
            return redirect()->route('pengurus.materials.index', array_merge(
                ['classroom_id' => $classroom->id],
                $currentFolderId ? ['folder_id' => $currentFolderId] : [],
                $request->input('iframe') ? ['iframe' => 1] : []
            ))->with('success', 'Materi berhasil ditambahkan ke Classroom.');
        }
        
        return back()->with('success', 'Materi berhasil ditambahkan ke Classroom.');
    }

    // --- Dynamic Remove Material ---
    public function removeMaterial(Request $request, $programId, $performanceId, $materialId)
    {
        return $this->processRemoveMaterial($this->getCommonClassroom($programId, $performanceId, null), $materialId);
    }

    public function removeJobMaterial(Request $request, $jobId, $materialId)
    {
        return $this->processRemoveMaterial($this->getCommonClassroom(null, null, $jobId), $materialId);
    }

    private function processRemoveMaterial(Classroom $classroom, $materialId)
    {
        $material = Material::where('classroom_id', $classroom->id)->findOrFail($materialId);
        $material->update(['classroom_id' => null]);
        return back()->with('success', 'Materi berhasil dihapus dari Classroom.');
    }

    // --- Dynamic Store Announcement ---
    public function storeAnnouncement(Request $request, $programId, $performanceId)
    {
        return $this->processStoreAnnouncement($request, $this->getCommonClassroom($programId, $performanceId, null));
    }

    public function storeJobAnnouncement(Request $request, $jobId)
    {
        return $this->processStoreAnnouncement($request, $this->getCommonClassroom(null, null, $jobId));
    }

    private function processStoreAnnouncement(Request $request, Classroom $classroom)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        Announcement::create([
            'classroom_id' => $classroom->id,
            'created_by'   => auth()->id(),
            'title'        => $request->title,
            'content'      => $request->content,
        ]);
        return back()->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    // --- Dynamic Destroy Announcement ---
    public function destroyAnnouncement($programId, $performanceId, $announcementId)
    {
        return $this->processDestroyAnnouncement($announcementId);
    }

    public function destroyJobAnnouncement($jobId, $announcementId)
    {
        return $this->processDestroyAnnouncement($announcementId);
    }

    private function processDestroyAnnouncement($announcementId)
    {
        Announcement::findOrFail($announcementId)->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    // --- Dynamic Store Schedule ---
    public function storeSchedule(Request $request, $programId, $performanceId)
    {
        return $this->processStoreSchedule($request, $this->getCommonClassroom($programId, $performanceId, null));
    }

    public function storeJobSchedule(Request $request, $jobId)
    {
        return $this->processStoreSchedule($request, $this->getCommonClassroom(null, null, $jobId));
    }

    private function processStoreSchedule(Request $request, Classroom $classroom)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'date'       => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
            'location'   => 'nullable|string|max:255',
            'notes'      => 'nullable|string',
        ]);

        ClassroomSchedule::create([
            'classroom_id' => $classroom->id,
            'title'        => $request->title,
            'date'         => $request->date,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'location'     => $request->location,
            'notes'        => $request->notes,
        ]);
        return back()->with('success', 'Jadwal latihan berhasil ditambahkan.');
    }

    // --- Dynamic Destroy Schedule ---
    public function destroySchedule($programId, $performanceId, $scheduleId)
    {
        return $this->processDestroySchedule($scheduleId);
    }

    public function destroyJobSchedule($jobId, $scheduleId)
    {
        return $this->processDestroySchedule($scheduleId);
    }

    private function processDestroySchedule($scheduleId)
    {
        ClassroomSchedule::findOrFail($scheduleId)->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    // --- Dynamic Store Song Target ---
    public function storeSongTarget(Request $request, $programId, $performanceId)
    {
        return $this->processStoreSongTarget($request, $this->getCommonClassroom($programId, $performanceId, null));
    }

    public function storeJobSongTarget(Request $request, $jobId)
    {
        return $this->processStoreSongTarget($request, $this->getCommonClassroom(null, null, $jobId));
    }

    private function processStoreSongTarget(Request $request, Classroom $classroom)
    {
        $request->validate([
            'song_title' => 'required|string|max:255',
            'composer'   => 'nullable|string|max:255',
            'voice_part' => 'nullable|string|max:100',
            'status'     => 'required|in:Belajar,Hafal,Siap Tampil',
            'notes'      => 'nullable|string',
        ]);

        ClassroomSongTarget::create([
            'classroom_id' => $classroom->id,
            'song_title'   => $request->song_title,
            'composer'     => $request->composer,
            'voice_part'   => $request->voice_part,
            'status'       => $request->status,
            'notes'        => $request->notes,
        ]);
        return back()->with('success', 'Target lagu berhasil ditambahkan.');
    }

    // --- Dynamic Update Song Target ---
    public function updateSongTarget(Request $request, $programId, $performanceId, $targetId)
    {
        return $this->processUpdateSongTarget($request, $targetId);
    }

    public function updateJobSongTarget(Request $request, $jobId, $targetId)
    {
        return $this->processUpdateSongTarget($request, $targetId);
    }

    private function processUpdateSongTarget(Request $request, $targetId)
    {
        $target = ClassroomSongTarget::findOrFail($targetId);
        $target->update(['status' => $request->status]);
        return back()->with('success', 'Status lagu diperbarui.');
    }

    // --- Dynamic Destroy Song Target ---
    public function destroySongTarget($programId, $performanceId, $targetId)
    {
        return $this->processDestroySongTarget($targetId);
    }

    public function destroyJobSongTarget($jobId, $targetId)
    {
        return $this->processDestroySongTarget($targetId);
    }

    private function processDestroySongTarget($targetId)
    {
        ClassroomSongTarget::findOrFail($targetId)->delete();
        return back()->with('success', 'Target lagu berhasil dihapus.');
    }

    // --- Dynamic Store Attendance ---
    public function storeAttendance(Request $request, $programId, $performanceId)
    {
        return $this->processStoreAttendance($request, $this->getCommonClassroom($programId, $performanceId, null), 'pengurus.programs.performance.classroom.attendance', [$programId, $performanceId]);
    }

    public function storeJobAttendance(Request $request, $jobId)
    {
        return $this->processStoreAttendance($request, $this->getCommonClassroom(null, null, $jobId), 'pengurus.jobs.classroom.attendance', [$jobId]);
    }

    private function processStoreAttendance(Request $request, Classroom $classroom, $routeName, $routeParams)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|in:Latihan,Gladi Resik,Penampilan',
            'date'  => 'required|date',
        ]);

        $attendance = Attendance::create([
            'classroom_id' => $classroom->id,
            'title'        => $request->title,
            'type'         => $request->type,
            'date'         => $request->date,
        ]);

        foreach ($classroom->members as $member) {
            AttendanceDetail::create([
                'attendance_id' => $attendance->id,
                'member_id'     => $member->id,
                'status'        => 'Alpha',
            ]);
        }

        return redirect()->route($routeName, array_merge($routeParams, [$attendance->id]))->with('success', 'Sesi absensi dibuat. Silakan isi kehadiran.');
    }

    // --- Dynamic Show Attendance ---
    public function showAttendance($programId, $performanceId, $attendanceId)
    {
        $performance = Performance::where('program_id', $programId)->findOrFail($performanceId);
        $classroom = $performance->classroom;
        $attendance = Attendance::with('details.member')->where('classroom_id', $classroom->id)->findOrFail($attendanceId);

        return view('pengurus.classrooms.attendance', compact('performance', 'classroom', 'attendance', 'programId', 'performanceId'));
    }

    public function showJobAttendance($jobId, $attendanceId)
    {
        $job = Job::findOrFail($jobId);
        $classroom = $job->classroom;
        $attendance = Attendance::with('details.member')->where('classroom_id', $classroom->id)->findOrFail($attendanceId);

        return view('pengurus.classrooms.attendance', compact('job', 'classroom', 'attendance'));
    }

    // --- Dynamic Save Attendance ---
    public function saveAttendance(Request $request, $programId, $performanceId, $attendanceId)
    {
        return $this->processSaveAttendance($request, $attendanceId);
    }

    public function saveJobAttendance(Request $request, $jobId, $attendanceId)
    {
        return $this->processSaveAttendance($request, $attendanceId);
    }

    private function processSaveAttendance(Request $request, $attendanceId)
    {
        $attendance = Attendance::findOrFail($attendanceId);

        $request->validate([
            'statuses'   => 'required|array',
            'statuses.*' => 'in:Hadir,Izin,Sakit,Alpha',
        ]);

        foreach ($request->statuses as $memberId => $status) {
            AttendanceDetail::updateOrCreate(
                ['attendance_id' => $attendanceId, 'member_id' => $memberId],
                ['status' => $status, 'notes' => $request->notes[$memberId] ?? null]
            );
        }

        return back()->with('success', 'Absensi berhasil disimpan.');
    }
}
