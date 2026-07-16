<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\Program;
use App\Models\Member;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function show($programId)
    {
        return redirect()->route('pengurus.programs.show', $programId);
    }

    public function store(Request $request, $programId)
    {
        $program = Program::findOrFail($programId);

        if ($program->activity_type !== 'Performance' && $program->activity_type !== 'Competition') {
            return back()->with('error', 'Program ini bukan bertipe Penampilan atau Kompetisi.');
        }

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'venue'            => 'required|string|max:255',
            'performance_date' => 'required|date',
            'performance_time' => 'nullable|date_format:H:i',
            'description'      => 'nullable|string',
            'status'           => 'required|in:Persiapan,Berlangsung,Selesai',
        ]);

        $data['program_id'] = $programId;

        // Remove old performance if exists before creating new one
        $program->performance()->delete();
        $performance = Performance::create($data);

        // Auto-create classroom
        $performance->classroom()->firstOrCreate(
            ['performance_id' => $performance->id],
            [
                'name'   => 'Pusat Latihan ' . $performance->title,
                'status' => 'Aktif',
            ]
        );

        return redirect()->route('pengurus.programs.show', $programId)
            ->with('success', 'Data Penampilan / Lomba berhasil disimpan.');
    }

    public function update(Request $request, $programId, $performanceId)
    {
        $performance = Performance::where('program_id', $programId)->findOrFail($performanceId);

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'venue'            => 'required|string|max:255',
            'performance_date' => 'required|date',
            'performance_time' => 'nullable|date_format:H:i',
            'description'      => 'nullable|string',
            'status'           => 'required|in:Persiapan,Berlangsung,Selesai',
        ]);

        $performance->update($data);

        // Sync classroom name
        $classroom = $performance->classroom()->firstOrCreate(
            ['performance_id' => $performance->id],
            [
                'name'   => 'Pusat Latihan ' . $performance->title,
                'status' => 'Aktif',
            ]
        );
        $classroom->update([
            'name' => 'Pusat Latihan ' . $performance->title
        ]);

        return back()->with('success', 'Data Penampilan / Lomba berhasil diperbarui.');
    }

    public function destroy($programId, $performanceId)
    {
        $performance = Performance::where('program_id', $programId)->findOrFail($performanceId);
        $performance->delete();

        return redirect()->route('pengurus.programs.show', $programId)
            ->with('success', 'Data Penampilan / Lomba berhasil dihapus.');
    }
}
