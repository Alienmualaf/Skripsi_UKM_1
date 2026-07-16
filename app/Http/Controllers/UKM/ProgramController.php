<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $programs = Program::with(['performance', 'report'])->latest()->get();
        $proker = $programs->where('activity_type', '!=', 'Performance');
        $performances = $programs->where('activity_type', 'Performance');
        $jobs = \App\Models\Performance::whereNull('program_id')->with('classroom.members.user')->latest()->get();

        return view('pengurus.programs.index', compact('proker', 'performances', 'jobs'));
    }

    public function create()
    {
        return view('pengurus.programs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'activity_type'  => 'required|in:Event,Competition,Performance',
            'event_category' => 'nullable|required_if:activity_type,Event|in:Internal,External',
            'venue'          => 'nullable|string|max:255',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'pic'            => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'show_on_landing'=> 'nullable|boolean',
            'performance_time' => 'nullable|string',
        ]);

        $data['show_on_landing'] = $request->has('show_on_landing');
        $data['division'] = 'Umum';
        $data['budget'] = 0;
        $data['progress'] = 0;

        // Auto calculate status based on start_date and end_date
        $today = now()->toDateString();
        $status = 'Perencanaan';
        if ($today > $data['end_date']) {
            $status = 'Selesai';
        } elseif ($today >= $data['start_date'] && $today <= $data['end_date']) {
            $status = 'Berjalan';
        }
        $data['status'] = $status;

        $program = Program::create($data);

        if ($program->activity_type === 'Performance' || $program->activity_type === 'Competition') {
            $prefix = $program->activity_type === 'Competition' ? 'Lomba ' : 'Penampilan ';
            $performance = $program->performance()->create([
                'title'            => $prefix . $program->name,
                'venue'            => $program->venue ?? 'Belum ditentukan',
                'performance_date' => $program->start_date,
                'performance_time' => $request->performance_time ?? '19:00:00',
                'status'           => 'Persiapan',
                'show_on_landing'  => $program->show_on_landing,
            ]);

            $performance->classroom()->create([
                'name'   => 'Pusat Latihan ' . $performance->title,
                'status' => 'Aktif',
            ]);
        }

        return redirect()->route('pengurus.programs.index')->with('success', 'Program kerja berhasil ditambahkan.');
    }

    public function show($id)
    {
        $program = Program::with(['performance.classroom', 'report'])->findOrFail($id);
        return view('pengurus.programs.show', compact('program'));
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);
        return view('pengurus.programs.edit', compact('program'));
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'activity_type'  => 'required|in:Event,Competition,Performance',
            'event_category' => 'nullable|required_if:activity_type,Event|in:Internal,External',
            'venue'          => 'nullable|string|max:255',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'pic'            => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'show_on_landing'=> 'nullable|boolean',
            'performance_time' => 'nullable|string',
        ]);

        $data['show_on_landing'] = $request->has('show_on_landing');

        // Auto calculate status based on start_date and end_date
        $today = now()->toDateString();
        $status = 'Perencanaan';
        if ($today > $data['end_date']) {
            $status = 'Selesai';
        } elseif ($today >= $data['start_date'] && $today <= $data['end_date']) {
            $status = 'Berjalan';
        }
        $data['status'] = $status;

        $program->update($data);

        if ($program->activity_type === 'Performance' || $program->activity_type === 'Competition') {
            $performance = $program->performance;
            $prefix = $program->activity_type === 'Competition' ? 'Lomba ' : 'Penampilan ';
            if (!$performance) {
                $performance = $program->performance()->create([
                    'title'            => $prefix . $program->name,
                    'venue'            => $program->venue ?? 'Belum ditentukan',
                    'performance_date' => $program->start_date,
                    'performance_time' => $request->performance_time ?? '19:00:00',
                    'status'           => 'Persiapan',
                    'show_on_landing'  => $program->show_on_landing,
                ]);
            } else {
                $performance->update([
                    'title'            => $prefix . $program->name,
                    'venue'            => $program->venue ?? 'Belum ditentukan',
                    'performance_date' => $program->start_date,
                    'performance_time' => $request->performance_time ?? $performance->performance_time ?? '19:00:00',
                    'show_on_landing'  => $program->show_on_landing,
                ]);
            }

            $performance->classroom()->firstOrCreate(
                ['performance_id' => $performance->id],
                [
                    'name'   => 'Pusat Latihan ' . $performance->title,
                    'status' => 'Aktif',
                ]
            );
        } else {
            $program->performance()->delete();
        }

        return redirect()->route('pengurus.programs.index')->with('success', 'Program kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();
        return back()->with('success', 'Program kerja berhasil dihapus.');
    }
}
