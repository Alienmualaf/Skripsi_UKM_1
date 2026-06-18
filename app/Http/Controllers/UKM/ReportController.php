<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\Inventory;
use App\Models\Letter;
use App\Models\Member;
use App\Models\Performance;
use App\Models\Program;
use App\Models\ProgramReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ─── HUB ─────────────────────────────────────────────────────────────────

    public function index()
    {
        $programs = Program::with('report')->orderBy('start_date', 'desc')->get();
        return view('ukm.reports.index', compact('programs'));
    }

    // ─── LAPORAN KEGIATAN ─────────────────────────────────────────────────────

    public function showKegiatan($programId)
    {
        $program = Program::with([
            'report.creator',
            'performance.classroom.members.voiceClassification',
        ])->findOrFail($programId);

        // Automatically create report if not exists (Laporan Kegiatan terbuat otomatis)
        if (!$program->report) {
            $expense = Finance::where('program_id', $programId)->where('type', 'expense')->sum('amount');
            $createdBy = auth()->id();
            if (!$createdBy) {
                $createdBy = \App\Models\User::first()?->id ?? 1;
            }

            $reportData = [
                'program_id' => $programId,
                'created_by' => $createdBy,
                'title' => 'Laporan Pertanggungjawaban ' . $program->name,
                'executive_summary' => $program->description ?? 'Laporan pelaksanaan program kerja ' . $program->name . '.',
                'activities_description' => $program->performance 
                    ? "Kegiatan ini merupakan penampilan dengan nama '" . $program->name . "' yang diselenggarakan pada tanggal " . ($program->performance->performance_date ? $program->performance->performance_date->format('d-m-Y') : '-') . " di " . ($program->performance->venue ?? '-') . "."
                    : "Kegiatan '" . $program->name . "' diselenggarakan oleh divisi " . $program->division . " dari tanggal " . ($program->start_date ? date('d-m-Y', strtotime($program->start_date)) : '-') . " sampai " . ($program->end_date ? date('d-m-Y', strtotime($program->end_date)) : '-') . ".",
                'budget_realization' => "Realisasi anggaran untuk program kerja " . $program->name . " adalah sebesar Rp " . number_format($expense, 0, ',', '.') . " dari rencana anggaran sebesar Rp " . number_format($program->budget, 0, ',', '.') . ".",
                'obstacles' => "Pelaksanaan program kerja berjalan dengan baik tanpa ada hambatan yang berarti.",
                'recommendations' => "Disarankan agar program kerja serupa dapat dilaksanakan kembali di periode mendatang dengan persiapan yang lebih matang.",
                'realized_budget' => $expense,
                'status' => 'Approved',
            ];

            ProgramReport::create($reportData);

            // Reload program report
            $program = Program::with([
                'report.creator',
                'performance.classroom.members.voiceClassification',
            ])->findOrFail($programId);
        }

        // Finances linked to program
        $finances = Finance::where('program_id', $programId)->orderBy('transaction_date', 'desc')->get();

        // Letters linked to program
        $letters = Letter::where('program_id', $programId)->orderBy('date', 'desc')->get();

        return view('ukm.reports.kegiatan', compact('program', 'finances', 'letters'));
    }

    public function storeKegiatan(Request $request, $programId)
    {
        $program = Program::findOrFail($programId);
        $data    = $request->validate([
            'title'                  => 'required|string|max:255',
            'executive_summary'      => 'nullable|string',
            'activities_description' => 'nullable|string',
            'budget_realization'     => 'nullable|string',
            'obstacles'              => 'nullable|string',
            'recommendations'        => 'nullable|string',
            'realized_budget'        => 'nullable|numeric|min:0',
            'status'                 => 'required|in:Draft,Submitted,Approved',
        ]);

        $data['program_id']  = $programId;
        $data['created_by']  = auth()->id();
        $data['realized_budget'] = $data['realized_budget'] ?? 0;

        ProgramReport::updateOrCreate(['program_id' => $programId], $data);

        return redirect()
            ->route('ukm.reports.kegiatan', $programId)
            ->with('success', 'Laporan Kegiatan berhasil disimpan.');
    }

    public function destroyKegiatan($programId)
    {
        ProgramReport::where('program_id', $programId)->firstOrFail()->delete();
        return redirect()->route('ukm.reports.index')
            ->with('success', 'Laporan Kegiatan berhasil dihapus.');
    }

    // ─── LAPORAN REKRUTMEN ────────────────────────────────────────────────────

    public function rekrutmen(Request $request)
    {
        $query = Member::with('voiceClassification')->orderBy('created_at', 'desc');

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $members    = $query->get();
        $tahunList  = Member::selectRaw('YEAR(created_at) as tahun')->distinct()->orderByDesc('tahun')->pluck('tahun');

        return view('ukm.reports.rekrutmen', compact('members', 'tahunList'));
    }

    // ─── LAPORAN KEUANGAN ─────────────────────────────────────────────────────

    public function keuangan(Request $request)
    {
        $query = Finance::with(['category', 'program'])->orderBy('transaction_date', 'desc');

        $filterMode = $request->filter_mode ?? 'custom'; // bulanan | tahunan | custom

        if ($filterMode === 'bulanan' && $request->filled('bulan')) {
            [$year, $month] = explode('-', $request->bulan);
            $query->whereYear('transaction_date', $year)
                  ->whereMonth('transaction_date', $month);
        } elseif ($filterMode === 'tahunan' && $request->filled('tahun')) {
            $query->whereYear('transaction_date', $request->tahun);
        } else {
            if ($request->filled('start_date')) $query->whereDate('transaction_date', '>=', $request->start_date);
            if ($request->filled('end_date'))   $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        $finances  = $query->get();
        $income    = $finances->where('type', 'income')->sum('amount');
        $expense   = $finances->where('type', 'expense')->sum('amount');
        $saldo     = $income - $expense;
        $tahunList = Finance::selectRaw('YEAR(transaction_date) as tahun')->distinct()->orderByDesc('tahun')->pluck('tahun');

        return view('ukm.reports.keuangan', compact('finances', 'income', 'expense', 'saldo', 'tahunList'));
    }

    // ─── LPJ ──────────────────────────────────────────────────────────────────

    public function lpj(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfYear()->toDateString();
        $endDate   = $request->end_date   ?? now()->toDateString();

        $programs     = Program::with(['report', 'performance'])
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orderBy('start_date')
            ->get();

        $performances = Performance::whereBetween('performance_date', [$startDate, $endDate])
            ->with('program')
            ->orderBy('performance_date')
            ->get();

        $finances  = Finance::whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date')
            ->get();

        $income  = $finances->where('type', 'income')->sum('amount');
        $expense = $finances->where('type', 'expense')->sum('amount');
        $saldo   = $income - $expense;

        $inventories = Inventory::with('loans')->get();

        $letters = Letter::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        $members = Member::with('voiceClassification')
            ->where('status', 'Anggota Aktif')
            ->get();

        return view('ukm.reports.lpj', compact(
            'startDate', 'endDate',
            'programs', 'performances',
            'finances', 'income', 'expense', 'saldo',
            'inventories', 'letters', 'members'
        ));
    }

    // ─── PRINT / PDF ─────────────────────────────────────────────────────────
    // These routes open a printable-only view (browser print dialog)

    public function printKegiatan($programId)
    {
        $program  = Program::with(['report.creator', 'performance.classroom.members.voiceClassification'])->findOrFail($programId);

        // Automatically create report if not exists (Laporan Kegiatan terbuat otomatis)
        if (!$program->report) {
            $expense = Finance::where('program_id', $programId)->where('type', 'expense')->sum('amount');
            $createdBy = auth()->id();
            if (!$createdBy) {
                $createdBy = \App\Models\User::first()?->id ?? 1;
            }

            $reportData = [
                'program_id' => $programId,
                'created_by' => $createdBy,
                'title' => 'Laporan Pertanggungjawaban ' . $program->name,
                'executive_summary' => $program->description ?? 'Laporan pelaksanaan program kerja ' . $program->name . '.',
                'activities_description' => $program->performance 
                    ? "Kegiatan ini merupakan penampilan dengan nama '" . $program->name . "' yang diselenggarakan pada tanggal " . ($program->performance->performance_date ? $program->performance->performance_date->format('d-m-Y') : '-') . " di " . ($program->performance->venue ?? '-') . "."
                    : "Kegiatan '" . $program->name . "' diselenggarakan oleh divisi " . $program->division . " dari tanggal " . ($program->start_date ? date('d-m-Y', strtotime($program->start_date)) : '-') . " sampai " . ($program->end_date ? date('d-m-Y', strtotime($program->end_date)) : '-') . ".",
                'budget_realization' => "Realisasi anggaran untuk program kerja " . $program->name . " adalah sebesar Rp " . number_format($expense, 0, ',', '.') . " dari rencana anggaran sebesar Rp " . number_format($program->budget, 0, ',', '.') . ".",
                'obstacles' => "Pelaksanaan program kerja berjalan dengan baik tanpa ada hambatan yang berarti.",
                'recommendations' => "Disarankan agar program kerja serupa dapat dilaksanakan kembali di periode mendatang dengan persiapan yang lebih matang.",
                'realized_budget' => $expense,
                'status' => 'Approved',
            ];

            ProgramReport::create($reportData);

            // Reload program report
            $program = Program::with(['report.creator', 'performance.classroom.members.voiceClassification'])->findOrFail($programId);
        }

        $finances = Finance::where('program_id', $programId)->orderBy('transaction_date', 'desc')->get();
        $letters  = Letter::where('program_id', $programId)->orderBy('date', 'desc')->get();
        return view('ukm.reports.print.kegiatan', compact('program', 'finances', 'letters'));
    }

    public function printRekrutmen(Request $request)
    {
        $query = Member::with('voiceClassification')->orderBy('created_at', 'desc');
        if ($request->filled('tahun'))      $query->whereYear('created_at', $request->tahun);
        if ($request->filled('start_date')) $query->whereDate('created_at', '>=', $request->start_date);
        if ($request->filled('end_date'))   $query->whereDate('created_at', '<=', $request->end_date);
        $members = $query->get();
        return view('ukm.reports.print.rekrutmen', compact('members'));
    }

    public function printKeuangan(Request $request)
    {
        $query = Finance::with(['category', 'program'])->orderBy('transaction_date', 'desc');
        if ($request->filled('start_date')) $query->whereDate('transaction_date', '>=', $request->start_date);
        if ($request->filled('end_date'))   $query->whereDate('transaction_date', '<=', $request->end_date);
        if ($request->filled('tahun'))      $query->whereYear('transaction_date', $request->tahun);
        $finances = $query->get();
        $income   = $finances->where('type', 'income')->sum('amount');
        $expense  = $finances->where('type', 'expense')->sum('amount');
        $saldo    = $income - $expense;
        return view('ukm.reports.print.keuangan', compact('finances', 'income', 'expense', 'saldo'));
    }

    public function printLpj(Request $request)
    {
        $startDate    = $request->start_date ?? now()->startOfYear()->toDateString();
        $endDate      = $request->end_date   ?? now()->toDateString();
        $programs     = Program::with(['report', 'performance'])->whereBetween('start_date', [$startDate, $endDate])->orderBy('start_date')->get();
        $performances = Performance::whereBetween('performance_date', [$startDate, $endDate])->with('program')->orderBy('performance_date')->get();
        $finances     = Finance::whereBetween('transaction_date', [$startDate, $endDate])->orderBy('transaction_date')->get();
        $income       = $finances->where('type', 'income')->sum('amount');
        $expense      = $finances->where('type', 'expense')->sum('amount');
        $saldo        = $income - $expense;
        $inventories  = Inventory::with('loans')->get();
        $letters      = Letter::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'desc')->get();
        $members      = Member::with('voiceClassification')->where('status', 'Anggota Aktif')->get();
        return view('ukm.reports.print.lpj', compact(
            'startDate', 'endDate', 'programs', 'performances',
            'finances', 'income', 'expense', 'saldo', 'inventories', 'letters', 'members'
        ));
    }
}
