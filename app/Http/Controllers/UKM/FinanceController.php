<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $ukmId = session('managed_ukm_id');
        // Ambil maksimal 10 transaksi keuangan terbaru
        $finances = Finance::with('event')->where('ukm_id', $ukmId)->orderBy('transaction_date', 'desc')->take(10)->get();
        // Hitung total transaksi keuangan
        $totalFinances = Finance::where('ukm_id', $ukmId)->count();
        $events = \App\Models\Event::where('ukm_id', $ukmId)->latest()->get();

        return view('ukm.finances.index', compact('finances', 'events', 'totalFinances'));
    }

    public function all()
    {
        $ukmId = session('managed_ukm_id');
        // Ambil seluruh riwayat transaksi keuangan
        $finances = Finance::with('event')->where('ukm_id', $ukmId)->orderBy('transaction_date', 'desc')->get();
        
        return view('ukm.finances.all', compact('finances'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
            'event_id' => 'nullable|exists:events,id',
        ]);

        Finance::create([
            'ukm_id' => session('managed_ukm_id'),
            'created_by' => auth()->id(),
            'title' => $request->title,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
            'event_id' => $request->event_id,
        ]);

        return back()->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function destroy($id)
    {

        $ukmId = session('managed_ukm_id');
        $finance = Finance::where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        $finance->delete();

        return back()->with('success', 'Transaksi berhasil dihapus');
    }

}