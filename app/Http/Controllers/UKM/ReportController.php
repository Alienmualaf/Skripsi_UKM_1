<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Finance;
use App\Models\Membership;
use App\Models\UKM;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PdfService;

class ReportController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function index()
    {
        $ukmId = session('managed_ukm_id');
        $events = Event::where('ukm_id', $ukmId)->orderBy('start_date', 'desc')->get();
        return view('ukm.reports.index', compact('events'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:events,finances,memberships,lpj',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'mode' => 'nullable|in:preview,download',
        ]);

        $ukmId = session('managed_ukm_id');
        $type = $request->type;
        $mode = $request->mode ?? 'download';
        
        $data = ['type' => $type, 'ukm' => UKM::findOrFail($ukmId)];

        if ($type === 'events') {
            $eventId = $request->event_id;
            if (!$eventId) return back()->with('error', 'Silakan pilih agenda.');
            $event = Event::with(['participants.user', 'finances'])->where('id', $eventId)->where('ukm_id', $ukmId)->firstOrFail();
            $data['event'] = $event;
            $data['finances'] = $event->finances;
        } 
        elseif ($type === 'finances') {
            $query = Finance::where('ukm_id', $ukmId);
            if ($request->start_date) $query->whereDate('transaction_date', '>=', $request->start_date);
            if ($request->end_date) $query->whereDate('transaction_date', '<=', $request->end_date);
            $data['finances'] = $query->get();
            $data['startDate'] = $request->start_date;
            $data['endDate'] = $request->end_date;
        }
        elseif ($type === 'memberships') {
            $query = Membership::with('user')->where('ukm_id', $ukmId)->where('status', 'approved')->where('role_in_ukm', '!=', 'admin');
            if ($request->start_date) $query->whereDate('created_at', '>=', $request->start_date);
            if ($request->end_date) $query->whereDate('created_at', '<=', $request->end_date);
            $data['memberships'] = $query->get();
            $data['startDate'] = $request->start_date;
            $data['endDate'] = $request->end_date;
        }
        else { // lpj
            $startDate = $request->start_date ?? '2000-01-01';
            $endDate = $request->end_date ?? date('Y-m-d');
            
            $data['startDate'] = $startDate;
            $data['endDate'] = $endDate;
            $data['events'] = Event::with('participants.user')->where('ukm_id', $ukmId)->whereDate('start_date', '>=', $startDate)->whereDate('start_date', '<=', $endDate)->get();
            $data['active_members'] = Membership::with('user')->where('ukm_id', $ukmId)->where('status', 'approved')->where('role_in_ukm', '!=', 'admin')->get();
            
            $preIncome = Finance::where('ukm_id', $ukmId)->whereDate('transaction_date', '<', $startDate)->where('type', 'income')->sum('amount');
            $preExpense = Finance::where('ukm_id', $ukmId)->whereDate('transaction_date', '<', $startDate)->where('type', 'expense')->sum('amount');
            $data['kas_awal'] = $preIncome - $preExpense;

            $data['finances'] = Finance::where('ukm_id', $ukmId)->whereDate('transaction_date', '>=', $startDate)->whereDate('transaction_date', '<=', $endDate)->orderBy('transaction_date', 'asc')->get();
            $data['inventories'] = Inventory::where('ukm_id', $ukmId)->get();
        }

        // Generate Logic
        if ($type === 'lpj' || $type === 'events') {
            $pdfContent = $this->pdfService->mergeReportWithSubmissions($data['ukm'], $data);
        } else {
            $pdfContent = $this->pdfService->generateGeneralReport($data['ukm'], $data);
        }

        $filename = "laporan_{$type}.pdf";
        if ($mode === 'preview') {
            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        }
        
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
