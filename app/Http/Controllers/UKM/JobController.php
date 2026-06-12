<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\Member;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Performance::whereNull('program_id')->orderBy('performance_date', 'desc')->paginate(10);
        return view('pengurus.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $members = Member::where('status', 'Anggota Aktif')->get();
        return view('pengurus.jobs.create', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:members,id',
            'show_on_landing' => 'nullable|boolean',
        ]);

        $performance = Performance::create([
            'program_id' => null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'venue' => $data['location'],
            'performance_date' => $data['date'],
            'institution' => 'Universitas Pancasila',
            'performance_time' => '17:00:00',
            'fee' => 0.00,
            'pic' => auth()->user()->name,
            'status' => 'Persiapan',
            'show_on_landing' => $request->has('show_on_landing'),
        ]);

        $memberIds = $request->input('member_ids', []);

        // Create classroom for the performance job
        $classroom = $performance->classroom()->create([
            'name' => 'Classroom ' . $performance->title,
            'status' => 'Aktif',
        ]);
        
        if (!empty($memberIds)) {
            $classroom->members()->sync($memberIds);
        }

        return redirect('/pengurus/programs?tab=job')->with('success', 'Job penampilan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $job = Performance::whereNull('program_id')->findOrFail($id);
        $members = Member::where('status', 'Anggota Aktif')->get();
        $selectedMemberIds = $job->members->pluck('id')->toArray();

        return view('pengurus.jobs.edit', compact('job', 'members', 'selectedMemberIds'));
    }

    public function update(Request $request, $id)
    {
        $job = Performance::whereNull('program_id')->findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:members,id',
            'show_on_landing' => 'nullable|boolean',
        ]);

        $job->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'venue' => $data['location'],
            'performance_date' => $data['date'],
            'show_on_landing' => $request->has('show_on_landing'),
        ]);

        $memberIds = $request->input('member_ids', []);

        // Ensure classroom exists and sync members
        $classroom = $job->classroom;
        if (!$classroom) {
            $classroom = $job->classroom()->create([
                'name' => 'Classroom ' . $job->title,
                'status' => 'Aktif',
            ]);
        } else {
            $classroom->update(['name' => 'Classroom ' . $job->title]);
        }
        $classroom->members()->sync($memberIds);

        return redirect('/pengurus/programs?tab=job')->with('success', 'Job penampilan berhasil diperbarui.');
    }

    public function assignMembers(Request $request, $id)
    {
        $job = Performance::whereNull('program_id')->findOrFail($id);
        $request->validate([
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:members,id',
        ]);

        $memberIds = $request->input('member_ids', []);

        // Sync classroom members
        $classroom = $job->classroom;
        if (!$classroom) {
            $classroom = $job->classroom()->create([
                'name' => 'Classroom ' . $job->title,
                'status' => 'Aktif',
            ]);
        }
        $classroom->members()->sync($memberIds);

        return back()->with('success', 'Anggota yang bertugas berhasil disinkronisasi.');
    }

    public function destroy($id)
    {
        $job = Performance::whereNull('program_id')->findOrFail($id);
        if ($job->classroom) {
            $job->classroom->members()->detach();
            $job->classroom->delete();
        }
        $job->delete();
        return back()->with('success', 'Job penampilan berhasil dihapus.');
    }
}
