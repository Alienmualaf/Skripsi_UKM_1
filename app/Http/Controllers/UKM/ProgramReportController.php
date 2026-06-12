<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramReport;
use Illuminate\Http\Request;

class ProgramReportController extends Controller
{
    public function show($programId)
    {
        $program = Program::with(['report.creator', 'performance'])->findOrFail($programId);
        $report = $program->report;

        return view('pengurus.programs.report', compact('program', 'report'));
    }

    public function store(Request $request, $programId)
    {
        $program = Program::findOrFail($programId);

        $data = $request->validate([
            'title'                  => 'required|string|max:255',
            'executive_summary'      => 'nullable|string',
            'activities_description' => 'nullable|string',
            'budget_realization'     => 'nullable|string',
            'obstacles'              => 'nullable|string',
            'recommendations'        => 'nullable|string',
            'realized_budget'        => 'nullable|numeric|min:0',
            'status'                 => 'required|in:Draft,Submitted,Approved',
        ]);

        $data['program_id'] = $programId;
        $data['created_by'] = auth()->id();
        $data['realized_budget'] = $data['realized_budget'] ?? 0;

        ProgramReport::updateOrCreate(
            ['program_id' => $programId],
            $data
        );

        return redirect()->route('pengurus.programs.report', $programId)
            ->with('success', 'Laporan Program Kerja (LPJ) berhasil disimpan.');
    }

    public function destroy($programId)
    {
        $report = ProgramReport::where('program_id', $programId)->firstOrFail();
        $report->delete();

        return redirect()->route('pengurus.programs.show', $programId)
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
